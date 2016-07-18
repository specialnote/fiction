<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $categoryKey
 * @property string $ditchKey
 * @property string $categoryRule
 * @property int $categoryNum
 * @property string $fictionRule
 * @property string $fictionLinkType
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'categoryNum'], 'integer'],
            [['name', 'categoryRule', 'fictionRule'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 100],
            [['categoryKey', 'ditchKey'], 'string', 'max' => 32],
            [['fictionLinkType'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'categoryKey' => 'Category Key',
            'ditchKey' => 'Ditch Key',
            'categoryRule' => 'Category Rule', //分类位置在页面中的选择器
            'categoryNum' => 'Category Num', //分类位置在页面中的选择器序号(0开始)
            'fictionRule' => 'Fiction Rule', //分类的所有小说列表在页面中的选择器
            'fictionLinkType' => 'Fiction Link Type', //章节列表链接类型。1表示current，是相对小说页面的相对地址；2表示home,即相对于渠道主页的地址
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    //初始化或者更新分类配置信息，保存到数据库，在console控制器中使用，不是定时任务，可以抛异常
    public static function updateCategoryInformation()
    {
        $config = new Config();
        //获取所有渠道分类信息
        $list = $config->getCategory();
        //更新数据库分类表
        foreach ($list as $k => $ditch) {
            foreach ($ditch as $v) {
                if ($v['category_key']) {
                    $category = self::find()->where(['ditchKey' => $k, 'categoryKey' => $v['category_key']])->one();
                    if (null === $category) {
                        $category = new self([
                            'categoryKey' => $v['category_key'],
                            'ditchKey' => $k,
                            'name' => $v['category_name'],
                            'url' => $v['category_url'],
                            'categoryRule' => $v['category_list_rule'],
                            'categoryNum' => $v['category_list_num'],
                            'fictionRule' => $v['list_link_rule'],
                            'fictionLinkType' => $v['category_list_link_type'],
                        ]);
                        $res = $category->save();
                        if (!$res) {
                            //todo 保存失败
                        }
                    }
                }
            }
        }
    }

    public function getDitch()
    {
        return $this->hasOne(Ditch::class, ['ditchKey' => 'ditchKey']);
    }
}
