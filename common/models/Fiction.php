<?php

namespace common\models;

use Goutte\Client;
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
    public $list;//章节列表

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
                if ($ditchKey && $categoryKey && $fictionList) {
                    $fiction = Fiction::find()->select('name')->asArray()->all();
                    $text = ArrayHelper::getColumn($fiction, 'name');
                    foreach ($fictionList as $v) {
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
                        $text[] = $v['text'];
                    }
                } else {
                    //todo 记录日志 从缓存中拿小说信息失败
                }
            } else {
                //todo 记录日志 分类缺少必要信息
            }
        }
    }

    //获取指定小说的详细信息包括章节列表
    public function getFunctionDetail()
    {
        $url = $this->url;
        $ditch = $this->getDitch();
        if ($ditch) {
            $chapterLinkType = $ditch->chapterLinkType;
            if ($chapterLinkType === 'current') {
                $refUrl = rtrim($url, '/') . '/';
            } elseif ($chapterLinkType === 'home') {
                $refUrl = $ditch->url;
            } else {
                $refUrl = '';
            }
            $detail = Gather::getFictionInformationAndChapterList($url, $ditch, $refUrl, true, true);
            if ($detail) {
                if ($detail['author']) {
                    $this->author = $detail['author'];
                    $pinyin = new Pinyin();
                    $this->fictionKey = implode($pinyin->convert($detail['author']));
                }
                if ($detail['description']) {
                    $this->description = $detail['description'];
                }
                if ($detail['list']) {
                    $this->list = $detail['list'];
                }
            }
        } else {
            //todo 记录日志 没有找到指定小说的渠道
        }
        return $this;
    }

    //更新指定小说的信息
    public function updateFictionDetail()
    {
        $url = $this->url;
        $ditch = $this->getDitch();
        if ($ditch) {
            $chapterLinkType = $ditch->chapterLinkType;
            if ($chapterLinkType === 'current') {
                $refUrl = rtrim($url, '/') . '/';
            } elseif ($chapterLinkType === 'home') {
                $refUrl = $ditch->url;
            } else {
                $refUrl = '';
            }
            $detail = Gather::getFictionInformationAndChapterList($url, $ditch, $refUrl, true, true);
            if ($detail) {
                if ($detail['author']) {
                    $this->author = $detail['author'];
                    $pinyin = new Pinyin();
                    $this->fictionKey = implode($pinyin->convert($detail['author']));
                }
                if ($detail['description']) {
                    $this->description = $detail['description'];
                }
                if ($detail['list']) {
                    $chapter = new Chapter();
                    $chapter->initChapter($this);
                    $chapter->createTable();
                    if ($chapter->hasTable()) {
                        $chapter->updateFictionChapter($detail['list']);
                    } else {
                        //todo 记录日志 没有数据表
                    }
                }
            }
        } else {
            //todo 记录日志 没有找到指定小说的渠道
        }
    }

    public function getDitch()
    {
        return Ditch::findOne(['ditchKey' => $this->ditchKey]);
    }

    //获取当前小说的章节列表
    public function getChapterList()
    {
        if ($this->list) {
            return $this->list;
        }
        $chapter = new Chapter([
            'ditchKey' => $this->ditchKey,
            'fictionId' => $this->id
        ]);
        $list = $chapter->getList();
        return $list;
    }

    //获取指定章节详情
    public function getDetail($num)
    {
        $key = 'ditch_' . $this->ditchKey . '_fiction_' . $this->id . '_chapter_' . $num;
        $cache = \Yii::$app->cache;
        $ditch = $this->getDitch();
        $chapter = (new Chapter())->initChapter($this);
        $list = $chapter->getChapter($num);
        if ($cache->exists($key)) {
            $content = $cache->get($key);
        } else {
            $content = '';
            if ($list && $list['url']) {
                $content = Gather::getFictionDetail($list['url'], $ditch->detailRule);
                if ($content) {
                    $cache->set($key, $content, 60 * 60 * 24);
                }
            }
        }
        $content = $content ?: '暂时没有找到指定章节数据';
        return ['text' => $list['text'], 'detail' => $content];
    }
}
