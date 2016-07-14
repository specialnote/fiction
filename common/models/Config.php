<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/14
 * Time: 17:34
 */

namespace common\models;


class Config
{
    public $ditch;

    public function __construct()
    {
        $this->ditch = \Yii::$app->params['ditch'];
    }

    /**
     * 获取指定或者全部渠道的配置
     * @param string $ditch_key
     * @return array
     * @throws \Exception
     */
    public function getConfig($ditch_key = ''){
        $config = $this->ditch;
        if ($ditch_key) {
            if (!isset($config[$ditch_key])) {
                throw new \Exception('指定渠道不存在');
            }
            return $config[$ditch_key];
        } else {
            return $config;
        }
    }

    /**
     * 获取指定渠道或者全部渠道的配置信息【单个渠道信息返回处理之后的一维数组】
     * @param string $ditch_key
     * @return array
     * @throws \Exception
     */
    public function getInformation($ditch_key = '')
    {
        $ditch = $this->getConfig($ditch_key);
        if (!$ditch_key) {
            return array_merge(
                [
                    'ditch_name' => $ditch['ditch_name'],
                    'ditch_key' => $ditch['ditch_key'],
                    'ditch_home_url' => $ditch['ditch_home_url']
                ],
                $ditch['ditch_rule']);
        } else {
            $config = [];
            foreach($ditch as $v){
                $config[] = array_merge(
                    [
                        'ditch_name' => $v['ditch_name'],
                        'ditch_key' => $v['ditch_key'],
                        'ditch_home_url' => $v['ditch_home_url']
                    ],
                    $v['ditch_rule']);
            }
            return $config;
        }
    }

    //获取指定渠道的分类列表
    public function getDitchCategoryList($ditch_key)
    {
        $config = $this->getConfig($ditch_key);
        return $config['category_list'];
    }
}