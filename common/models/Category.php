<?php

namespace common\models;


use Goutte\Client;
use yii\base\Exception;
use yii\base\Model;
use Yii;

class Category extends Model
{
    /**
     * 获取指定渠道的分类列表
     * @param $dk
     * @return array
     */
    public static function getDitchCategoryList($dk)
    {
        if (isset(Yii::$app->params['ditch'][$dk]['category_list'])) {
            $category = Yii::$app->params['ditch'][$dk]['category_list'];
        } else {
            $category = [];
        }
        return $category;
    }

    /**
     * @param $dk
     * @param $ck
     * @return array|mixed
     */
    public static function getDitchCategoryFictionList($dk, $ck)
    {
        if (isset(Yii::$app->params['ditch'][$dk]['category_list'][$ck])) {
            $homePage = Yii::$app->params['ditch'][$dk]['ditch_domain'];
            $category = Yii::$app->params['ditch'][$dk]['category_list'][$ck];
            if ($category && is_array($category['category_url']) && count($category['category_url']) > 0) {
                $client = new Client();
                $cache = Yii::$app->cache;
                $list = $cache->get('ditch_' . $dk . '_category_' . $ck . '_list');
                if (empty($list)) {
                    $list = [];
                    foreach ($category['category_url'] as $url) {
                        try {
                            $crawler = $client->request('GET', $url);
                            if ($crawler) {
                                $c = $crawler->filter($category['category_list_rule']);
                                if (isset($category['category_list_num'])) {
                                    $c = $c->eq(0);
                                }
                                global $list;
                                $c->filter($category['list_link_rule'])->each(function ($node) use ($list, $homePage, $category) {
                                    global $list;
                                    if ($node) {
                                        $text = $node->text();
                                        $href = $node->attr('href');
                                        if ($text && $href) {
                                            if ($category['category_list_link_type'] === 'home') {
                                                $href = rtrim($homePage, '/') . '/' . $href;
                                            }
                                            $list[] = ['url' => $href, 'text' => $text];
                                        }
                                    }
                                });
                            }
                        } catch (Exception $e) {

                        }
                    }
                    if (count($list) > 0) {
                        $cache->set('ditch_' . $dk . '_category_' . $ck . '_list', $list, Yii::$app->params['category_fiction_list_cache_expire_time']);
                    }
                }
                if (count($list) > 0) {
                    return $list;
                }
            }
        }
        return [];
    }

    /**
     * 获取指定渠道指定分类的配置信息
     * @param $dk
     * @param $ck
     * @return array
     */
    public static function getDitchCategory($dk, $ck) {
        if (isset(Yii::$app->params['ditch'][$dk]['category_list'][$ck])) {
            $category = Yii::$app->params['ditch'][$dk]['category_list'][$ck];
        } else {
            $category = [];
        }
        return $category;
    }
}