<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/14
 * Time: 17:34.
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
     * 获取指定或者全部渠道的配置.
     *
     * @param string $ditchKey
     *
     * @return array 渠道配置信息构成的二维数组
     *
     * @throws \Exception
     */
    public function getConfig($ditchKey = '')
    {
        $res = [];
        $config = $this->ditch;
        if ($ditchKey) {
            if (!isset($config[$ditchKey])) {
                throw new \Exception('指定渠道不存在');
            }
            $res[] = $config[$ditchKey];
        } else {
            $res = $config;
        }

        return $res;
    }

    /**
     * 获取指定渠道或者全部渠道的配置信息【单个渠道信息返回处理之后的一维数组】.
     *
     * @param string $ditchKey
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getInformation($ditchKey = '')
    {
        $ditch = $this->getConfig($ditchKey);
        $config = [];
        foreach ($ditch as $v) {
            $config[] = array_merge(
                [
                    'ditch_name' => $v['ditch_name'],
                    'ditch_key' => $v['ditch_key'],
                    'ditch_home_url' => $v['ditch_home_url'],
                ],
                $v['ditch_rule']);
        }

        return $config;
    }

    /**
     * 获取所有渠道的分类列表.
     *
     * @param string $ditchKey
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getCategory($ditchKey = '')
    {
        $ditch = $this->getConfig($ditchKey);
        $res = [];
        if ($ditch) {
            foreach ($ditch as $v) {
                if (isset($v['category_list'])) {
                    $res[$v['ditch_key']] = $v['category_list'];
                }
            }
        } else {
            throw new \Exception('没有找到渠道配置');
        }
        //var_dump($res['biquge']);die;
        return $res;
    }
}
