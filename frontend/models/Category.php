<?php

namespace frontend\models;

use yii\base\Exception;
use yii\base\Model;
use Yii;

class Category extends Model
{
    /**
     * 获取指定渠道的分类列表 或者 所有渠道的分类.
     *
     * @param $ditch_key
     *
     * @return array
     */
    public static function getDitchCategoryList($ditch_key = '')
    {
        $ditch = Yii::$app->params['ditch'];
        if (!$ditch_key) {
            $ditch_keys = array_keys($ditch);
            if (count($ditch_keys) > 0) {
                $category = [];
                foreach ($ditch_keys as $ditch_key) {
                    if (isset($ditch[$ditch_key]['category_list'])) {
                        $category = array_merge($category, $ditch[$ditch_key]['category_list']);
                    }
                }

                return $category;
            }
        } else {
            if (isset($ditch[$ditch_key]['category_list'])) {
                return $ditch[$ditch_key]['category_list'];
            }
        }

        return [];
    }

    /**
     * 获取指定渠道指定分类的配置信息.
     *
     * @param $ditch_key
     * @param $ck
     *
     * @return array
     */
    public static function getDitchCategory($ditch_key, $ck)
    {
        if (isset(Yii::$app->params['ditch'][$ditch_key]['category_list'][$ck])) {
            $category = Yii::$app->params['ditch'][$ditch_key]['category_list'][$ck];
        } else {
            $category = [];
        }

        return $category;
    }

    /**
     * 获取指定分类的小说列表并缓存.
     *
     * @param $ditch_key
     * @param $category_key
     *
     * @return array|mixed
     */
    public static function getDitchCategoryFictionList($ditch_key, $category_key)
    {
        $cache = Yii::$app->cache;
        $list = $cache->get('ditch_'.$ditch_key.'_category_'.$category_key.'_list');
        if (!$list) {
            $list = Gather::getDitchCategoryFictionList($ditch_key, $category_key);
            if ($list) {
                $cache->set('ditch_'.$ditch_key.'_category_'.$category_key.'_list', $list, Yii::$app->params['category_fiction_list_cache_expire_time']);
            }
        }

        return $list;
    }

    /**
     * 根据小说地址，获取小说章节列表，并缓存.
     *
     * @param $url
     * @param $ditch_key
     *
     * @return array
     *
     * @throws Exception
     */
    public static function getFictionDetail($url, $ditch_key)
    {
        $fictionInformationAndChapterList = Gather::getFictionInformationAndChapterList($url, $ditch_key);
        $fiction_information = $fictionInformationAndChapterList['fiction_information'];
        $fiction_chapter_list = $fictionInformationAndChapterList['fiction_chapter_list'];
        if (!isset($fiction_information['fiction_key'])) {
            throw new Exception();
        }
        $fiction_key = $fiction_information['fiction_key'];
        $cache = Yii::$app->cache;
        //缓存小说信息
        $chapterConfig = $cache->get('ditch_'.$ditch_key.'_fiction_'.$fiction_key.'_config');
        if (!$chapterConfig) {
            $cache->set(
                'ditch_'.$ditch_key.'_fiction_'.$fiction_key.'_config', $fiction_information, Yii::$app->params['fiction_configure_cache_expire_time']
            );
        }
        //缓存小说章节列表
        $list = $cache->get('ditch_'.$ditch_key.'_fiction_detail'.$fiction_key.'_fiction_list');
        if (!$list) {
            $cache->set('ditch_'.$ditch_key.'_fiction_detail'.$fiction_key.'_fiction_list', $list, Yii::$app->params['fiction_chapter_list_cache_expire_time']);
        }
        //返回小说详情
        return [
            'title' => $fiction_information['fiction_name'],
            'fiction_key' => $fiction_key,
            'author' => $fiction_information['fiction_author'],
            'description' => $fiction_information['fiction_introduction'],
            'list' => $fiction_chapter_list,
        ];
    }
}
