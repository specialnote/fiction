<?php

namespace common\models;

use Overtrue\Pinyin\Pinyin;
use yii\db\ActiveRecord;
use common\models\Ditch;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%fiction}}".
 *
 * @property int $id
 * @property string $categoryKey
 * @property string $fictionKey
 * @property string $ditchKey
 * @property string $name
 * @property string $description
 * @property string $author
 * @property string $url
 * @property int $status
 */
class Fiction extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fiction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status'], 'integer'],
            [['categoryKey', 'ditchKey'], 'string', 'max' => 80],
            [['fictionKey'], 'string', 'max' => 100],
            [['name', 'author', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryKey' => 'Category Key',
            'fictionKey' => 'Fiction Key',
            'ditchKey' => 'Ditch Key',
            'name' => 'Name',
            'description' => 'Description',
            'author' => 'Author',
            'url' => 'Url',
            'status' => 'Status',
        ];
    }

    public function getFictionKey()
    {
        if ($this->fictionKey) {
            return $this->fictionKey;
        }
        if ($this->name) {
            $pinyin = new Pinyin();
            $this->fictionKey = implode($pinyin->convert($this->name));
            $this->save(false);
            return $this->fictionKey;
        }
        return null;
    }

    /**
     * 更新所有分类的小说信息.
     */
    public static function updateCategoryFictionList()
    {
        @ini_set('memory_limit', '256M');
        //获取所有分类
        $categories = Category::find()->all();
        foreach ($categories as $category) {
            $ditchKey = $category->ditchKey;
            $categoryKey = $category->categoryKey;
            $url = $category->url;
            $categoryRule = $category->categoryRule;
            $categoryNum = $category->categoryNum;
            $fictionRule = $category->fictionRule;
            $fictionLinkType = $category->fictionLinkType;
            if ($ditchKey && $categoryKey && $url && $categoryRule && $fictionRule) {
                //根据小说链接类型 获取小说链接地址的相对地址
                if ($fictionLinkType === 'home') {
                    $ditch = $category->ditch;
                    if (!$ditch) {
                        //todo 记录日志 未找到指定小说的渠道
                        continue;
                    }
                    $refUrl = $ditch->url;
                } elseif ($fictionLinkType === 'current') {
                    $refUrl = $url;
                } else {
                    $refUrl = '';
                }
                $fictionList = Gather::gatherCategoryFictionList($url, $categoryRule, $fictionRule, $categoryNum, $refUrl);
                $cacheFictionList = ['ditchKey' => $ditchKey, 'categoryKey' => $categoryKey, 'list' => $fictionList];
            } else {
                //todo 记录日志 分类缺少必要信息
            }
        }
        //将分类的小说列表缓存下来
        if (isset($cacheFictionList) && count($cacheFictionList)) {
            $cache = \Yii::$app->cache;
            if ($cache->exists('cache_category_fiction_list')) {
                $cache->delete('cache_category_fiction_list');
            }
            $cache->set('cache_category_fiction_list', $cacheFictionList, 60 * 60 * 24 * 7);
        }
    }

    //从缓存中读取数据并更新小说数据库（定时任务,每月执行一次）
    public static function updateFictionWithCache()
    {
        $cache = \Yii::$app->cache;
        if ($cache->exists('cache_category_fiction_list')) {
            $cacheFictionList = $cache->get('cache_category_fiction_list');
            if ($cacheFictionList) {
                $ditchKey = $cacheFictionList['ditchKey'];
                $categoryKey = $cacheFictionList['categoryKey'];
                $list = $cacheFictionList['list'];
                if ($ditchKey && $categoryKey && $list) {
                    $data = [];
                    $fiction = [];
                    foreach ($list as $v) {
                        if ($v['text'] && $v['url'] && !in_array($v['text'], $fiction)) {
                            $data[] = [$ditchKey, $categoryKey, $v['text'], $v['url'], 1];
                        }
                        $fiction[] = $v['text'];
                    }
                    $fiction = Fiction::find()->where(['ditchKey' => $ditchKey])->all();
                    if (count($fiction) === 0 && count($data)) {
                        //初始化数据
                        \Yii::$app->db->createCommand()->batchInsert('fiction', ['ditchKey', 'categoryKey', 'name', 'url', 'status'], $data)->execute();
                    } else {
                        //之后更新
                        $text = ArrayHelper::getColumn($fiction, 'name');
                        foreach ($data as $v) {
                            if (in_array($v['text'], $text)) {
                                continue;
                            }
                            $model = new Fiction([
                                'ditchKey' => $ditchKey,
                                'categoryKey' => $categoryKey,
                                'name' => trim($v['text']),
                                'url' => trim($v['url']),
                                'status' => 1,
                            ]);
                            $model->save();
                        }
                    }
                } else {
                    //todo 记录日志 从缓存中拿小说信息失败
                }
            }
            $cache->delete('cache_category_fiction_list');
        }
    }

    //更新所有小说的章节列表，放入缓存
    public static function updateFictionChapterList($limit = 10)
    {
        $fictions = Fiction::find()->where(['fiction.status' => 1])->joinWith('ditch');
        $fictions = $fictions->limit($limit)->all();
        $fictionList = [];
        foreach ($fictions as $fiction) {
            $url = $fiction->url;
            $ditch = $fiction->ditch;
            if ($ditch) {
                $chapterLinkType = $ditch->chapterLinkType;
                if ($chapterLinkType === 'current') {
                    $refUrl = rtrim($url, '/') . '/';
                } elseif ($chapterLinkType === 'home') {
                    $refUrl = $ditch->url;
                } else {
                    $refUrl = '';
                }
                $detail = Gather::getFictionInformationAndChapterList($url, $ditch, $refUrl);
                $fictionList[$fiction->id] = $detail;
            } else {
                //todo 记录日志 没有找到指定小说的渠道
            }
        }
        //缓存所有小说的章节列表
        if ($fictionList) {
            $cache = \Yii::$app->cache;
            if ($cache->exists('fiction_chapter_list')) {
                $list = $cache->get('fiction_chapter_list');
                $fictionList = array_merge($list, $fictionList);
            }
            $cache->set('fiction_chapter_list', $fictionList, 60 * 60 * 24 * 7);
        }
    }

    //根据缓存内容，更新小说章节列表
    public static function updateFictionChapterListWithCache()
    {
        $detail = [];
        $fiction = new Fiction();
        if ($detail) {
            if ($detail['author']) {
                $fiction->author = $detail['author'];
            }
            if ($detail['description']) {
                $fiction->description = $detail['description'];
            }
            if ($detail['list']) {
                $chapter = new Chapter();
                $chapter->initChapter($fiction);
                $chapter->createTable();
                if ($chapter->hasTable()) {
                    $chapter->updateFictionChapter($detail['list']);
                } else {
                    //todo 记录日志 没有数据表
                }
            }
        }
    }

    public function getDitch()
    {
        return $this->hasOne(Ditch::class, ['ditchKey' => 'ditchKey']);
    }
}
