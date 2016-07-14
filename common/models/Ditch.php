<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ditch}}".
 *
 * @property integer $id
 * @property integer $type 渠道类型，可以自定义
 * @property string $url 渠道主页地址
 * @property string $name 渠道名称
 * @property integer $status 渠道状态 1 已上线；2 未上线；3 已删除
 * @property string $ditch_key 渠道key，名称全拼组成
 * @property string $titleRule 小说标题采集规则
 * @property integer $titleNum 小说标题DOM序号
 * @property string $authorRule
 * @property integer $authorNum
 * @property string $descriptionRule
 * @property integer $descriptionNum
 * @property string $captionRule
 * @property string $detailRule
 * @property integer $captionLinkType 章节列表链接类型。1表示current，是相对小说页面的相对地址；2表示home,即相对于渠道主页的地址
 */
class Ditch extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ditch}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'titleNum', 'authorNum', 'descriptionNum', 'captionLinkType'], 'integer'],
            [['url'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 50],
            [['ditch_key'], 'string', 'max' => 80],
            [['titleRule', 'authorRule', 'descriptionRule', 'captionRule', 'detailRule'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',//渠道类型，可以自定义
            'url' => 'Url',//渠道主页地址
            'name' => 'Name',//渠道名称
            'status' => 'Status',//渠道状态 1 已上线；2 未上线；3 已删除
            'ditch_key' => 'Ditch Key',//渠道key，名称全拼组成
            'titleRule' => 'Title Rule',//小说标的采集规则
            'titleNum' => 'Title Num',//小说标的DOM序号
            'authorRule' => 'Author Rule',
            'authorNum' => 'Author Num',
            'descriptionRule' => 'Description Rule',
            'descriptionNum' => 'Description Num',
            'captionRule' => 'Caption Rule',
            'detailRule' => 'Detail Rule',
            'captionLinkType' => 'Caption Link Type',//章节列表链接类型。1表示current，是相对小说页面的相对地址；2表示home,即相对于渠道主页的地址
        ];
    }

    //初始化或者更新渠道配置信息，保存到数据库，在console控制器中使用，不是定时任务，可以抛异常
    public static function updateDitchInformation()
    {
        $config = new Config();
        //获取所有渠道信息
        $info = $config->getInformation();
        if (!$info){
            throw  new \Exception('没有找到渠道相关配置');
        }
        foreach ($info as $v){
            $ditch_key = $v['ditch_key'];
            if (!$ditch_key){
                continue;
            }
            $ditch = Ditch::find()->where(['ditch_key' => $v['ditch_key']])->one();
            if (!$ditch){
                $ditch = new Ditch();
            }
            $ditch->configureDitch($v);
            $ditch->save();
        }
    }

    /**
     * @param array $config 渠道信息配置数组（经过处理之后的一维数组）
     * @return Ditch
     * @throws \Exception
     */
    private function configureDitch($config)
    {
        //todo 更新渠道
        if (isset($config['ditch_name']) && isset($config['ditch_key']) && isset($config['ditch_home_url'])) {
            $this->ditch_key = $config['ditch_key'];
            $this->name = $config['ditch_name'];
            $this->url = $config['ditch_home_url'];
            $this->titleRule = $config['title_rule'];
            $this->titleNum = $config['title_rule_num'];
            $this->authorRule = $config['author_rule'];
            $this->authorNum = $config['author_rule_num'];
            $this->descriptionRule = $config['description_rule'];
            $this->descriptionNum = $config['description_rule_num'];
            $this->captionRule = $config['caption_list_rule'];
            $this->captionLinkType = $config['caption_list_type'];
            $this->detailRule = $config['fiction_detail_rule'];
        } else {
            throw new \Exception('缺少核心配置参数');
        }
        return $this;
    }
}
