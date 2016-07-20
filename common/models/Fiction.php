<?php

namespace common\models;

use Overtrue\Pinyin\Pinyin;
use yii\db\ActiveRecord;
use common\models\Ditch;

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

    /**
     * 更新所有分类的小说信息.
     */
    public static function updateCategoryFictionList()
    {
        //获取所有分类
        $categories = Category::find()->all();
        $pinyin = new Pinyin();
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
                if ($fictionList) {
                    foreach ($fictionList as $v) {
                        if ($v['url'] && $v['text']) {
                            $fictionKey = implode($pinyin->convert($v['text']));
                            $fiction = self::find()->where(['ditchKey' => $ditchKey, 'fictionKey' => $fictionKey])->one();
                            if (null === $fiction) {
                                $fiction = new self();
                                $fiction->ditchKey = $ditchKey;
                                $fiction->categoryKey = $categoryKey;
                                $fiction->fictionKey = $fictionKey;
                                $fiction->status = 1;
                            }
                            $fiction->url = $v['url'];
                            $fiction->name = $v['text'];
                            $res = $fiction->save();
                            if (!$res) {
                                //todo 添加日志 更新小说信息失败
                            }
                        }
                    }
                }
            } else {
                //todo 记录日志 分类缺少必要信息
            }
        }
    }

    //更新所有小说的章节列表
    public static function updateFictionChapterList()
    {
        $fictions = Fiction::find()->where(['fiction.status' => 1])->joinWith('ditch')->limit(1)->all();
        foreach ($fictions as $fiction) {
            $url = $fiction->url;
            $ditch = $fiction->ditch;
            if ($ditch) {
                $captionLinkType = $ditch->captionLinkType;
                if ($captionLinkType === 'current') {
                    $refUrl = rtrim($url, '/') . '/';
                } elseif ($captionLinkType === 'home') {
                    $refUrl = $ditch->url;
                } else {
                    $refUrl = '';
                }
                $detail = Gather::getFictionInformationAndCaptionList($url, $ditch, $refUrl);
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
                        var_dump($chapter->hasTable());die;
                        if ($chapter->hasTable) {
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
    }

    public function getDitch()
    {
        return $this->hasOne(Ditch::class, ['ditchKey' => 'ditchKey']);
    }
}
