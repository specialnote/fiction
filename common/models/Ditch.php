<?php

namespace common\models;


class Ditch
{
    public $ditch_key;

    public function __construct($ditch_key)
    {
        $this->ditch_key = $ditch_key;
    }

    /**
     * 获取指定渠道的小说分类配置列表
     * @return array
     */
    public function getCategoryListConfig()
    {
        if (isset(\Yii::$app->params['ditch'][$this->ditch_key]['category_list'])) {
            return \Yii::$app->params['ditch'][$this->ditch_key]['category_list'];
        } else {
         return [];
        }
    }

    /**
     * 获取指定渠道的小说采集规则（包括章节列表规则、小说详情规则）
     * @return array
     */
    public function getFictionRule()
    {
        if (isset(\Yii::$app->params['ditch'][$this->ditch_key]['fiction_rule'])) {
            return \Yii::$app->params['ditch'][$this->ditch_key]['fiction_rule'];
        } else {
            return [];
        }
    }
}