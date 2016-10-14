<?php

namespace common\models;

use Goutte\Client;
use Overtrue\Pinyin\Pinyin;
use yii\db\ActiveRecord;
use common\models\Ditch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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
 * @property int $status 1:正常 2:更新失败
 * @property int $views
 * @property string $imgUrl
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
            [['status', 'views'], 'integer'],
            [['categoryKey', 'ditchKey'], 'string', 'max' => 80],
            [['fictionKey', 'imgUrl'], 'string', 'max' => 100],
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
            'views' => '浏览次数',
            'imgUrl' => '图片地址',
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
                        $log = new Log([
                            'type' => Log::LOG_TYPE_CONFIG,
                            'model' => Fiction::class,
                            'function' => __FUNCTION__,
                            'work' => '根据分类查找对应的渠道',
                            'note' => serialize($category),
                        ]);
                        $log->save();
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
                    $log = new Log([
                        'type' => Log::LOG_TYPE_GATHER,
                        'model' => Fiction::class,
                        'function' => __FUNCTION__,
                        'work' => '根据分类采集小说列表(分类key：'.$categoryKey.')',
                        'note' => serialize($category),
                    ]);
                    $log->save();
                }
            } else {
                $log = new Log([
                    'type' => Log::LOG_TYPE_CONFIG,
                    'model' => Fiction::class,
                    'function' => __FUNCTION__,
                    'work' => '根据分类id、渠道关于分类及分类中小说列表的配置采集小说列表',
                    'note' => serialize($category),
                ]);
                $log->save();
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
            $log = new Log([
                'type' => Log::LOG_TYPE_GATHER,
                'model' => Fiction::class,
                'function' => __FUNCTION__,
                'work' => '获取指定小说的信息（作者、描述、章节列表）',
                'note' => serialize($this),
            ]);
            $log->save();
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
                $this->save();
                if ($detail['list']) {
                    $chapter = new Chapter();
                    $chapter->initChapter($this);
                    $chapter->createTable();
                    if ($chapter->hasTable()) {
                        $chapter->updateFictionChapter($detail['list']);
                    } else {
                        $log = new Log([
                            'type' => Log::LOG_TYPE_DB,
                            'model' => Fiction::class,
                            'function' => __FUNCTION__,
                            'work' => '保存指定小说的信息（章节列表）',
                            'note' => serialize($this),
                        ]);
                        $log->save();
                    }
                }
            }
        } else {
            $log = new Log([
                'type' => Log::LOG_TYPE_CONFIG,
                'model' => Fiction::class,
                'function' => __FUNCTION__,
                'work' => '保存指定小说的信息',
                'note' => serialize($this),
            ]);
            $log->save();
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
        $content = $cache->get($key);
        $chapter = (new Chapter())->initChapter($this);
        $list = $chapter->getChapter($num);
        if (!$content) {
            $ditch = $this->getDitch();
            $content = '';
            if ($list && $list['url']) {
                $content = Gather::getFictionDetail($list['url'], $ditch->detailRule);
            }
            if ($content) {
                $cache->set($key, $content, \Yii::$app->params['fiction_chapter_detail']);
            }
        }
        $content = $content ?: '暂时没有找到指定章节数据';
        return ['text' => $list['text'], 'detail' => $content];
    }

    //获取章节列表数
    public function getChapterCount()
    {
        $chapter = (new Chapter())->initChapter($this);
        return $chapter->getMaxNum();
    }

    //缓存指定章节详情
    public function cache($num)
    {
        $key = 'ditch_' . $this->ditchKey . '_fiction_' . $this->id . '_chapter_' . $num;
        $cache = \Yii::$app->cache;
        $content = $cache->get($key);
        if (!$content) {
            $ditch = $this->getDitch();
            $chapter = (new Chapter())->initChapter($this);
            $list = $chapter->getChapter($num);
            if ($list && $list['url']) {
                $content = Gather::getFictionDetail($list['url'], $ditch->detailRule);
                if ($content) {
                    $res = $cache->set($key, $content, \Yii::$app->params['fiction_chapter_detail']);
                    return $res;
                }
            }
        }
        return false;
    }

    //获取所有小说的地址
    public static function getAllFictionUrl()
    {
        $ids = self::find()->select('id')->all();
        $ids = ArrayHelper::getColumn($ids, 'id');
        return self::getFictionUrls($ids);
    }

    //获取指定小说的url
    public static function getFictionUrls(array $ids)
    {
        $host = rtrim(\Yii::$app->params['frontend_host'], '/');
        $urls = [];
        foreach ($ids as $id) {
            $urls[] = 'http://' . $host . '/fic/index?id=' . $id;
        }
        return $urls;
    }

    public static function getDetailUrls(array $data)
    {
        $host = rtrim(\Yii::$app->params['frontend_host'], '/');
        $urls = [];
        foreach ($data as $val) {
            if (isset($val['id']) && isset($val['num'])) {
                $urls[] = 'http://' . $host . 'fic/detail?fid=' . $val['id'] . '&num=' . $val['num'];
            }
        }
        return $urls;
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['categoryKey' => 'categoryKey']);
    }

    public function updateImgUrl()
    {
        if (!$this->imgUrl && $this->url && 1 === $this->status) {
            $imgUrl = Gather::getFictionImgUrl(rtrim($this->url));
            if ($imgUrl) {
                $ext = substr($imgUrl, strrpos($imgUrl, '.') + 1);
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif'])) {
                    $file = Image::download($imgUrl, $this->id.'.'.$ext);
                    $host = rtrim(\Yii::$app->params['frontend_host'], '/');
                    $url = 'http://' . $host . $file;
                    $headers = get_headers($url);
                    if (!empty($headers)
                        && !empty($headers[0])
                        && strpos($headers[0], '200')
                    ) {
                        $this->imgUrl =  $url;
                    } else {
                        $this->imgUrl = 'http://'.$host.'/images/default.svg';
                    }
                    $this->save();
                }
            }
        }
    }
}
