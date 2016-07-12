<?php

namespace common\models;


use Goutte\Client;
use Overtrue\Pinyin\Pinyin;
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
     * 获取指定分类的小说列表
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
    public static function getDitchCategory($dk, $ck)
    {
        if (isset(Yii::$app->params['ditch'][$dk]['category_list'][$ck])) {
            $category = Yii::$app->params['ditch'][$dk]['category_list'][$ck];
        } else {
            $category = [];
        }
        return $category;
    }

    /**
     * 根据小说地址，获取小说章节列表，并缓存
     * @param $url
     * @param $dk
     * @return array
     */
    public static function getFictionDetail($url, $dk)
    {
        $ditch = new Ditch($dk);
        $rule = $ditch->getFictionRule();//获取渠道采集规则
        if ($ditch && $rule) {
            $rule = $rule['fiction_caption_list_rule'];
            $client = new Client();
            if ($url) {
                $crawler = $client->request('GET', $url);
                try {
                    //获取小说信息
                    $pinyin = new Pinyin();
                    $title = $crawler->filter($rule['fiction_title_rule'])->eq($rule['fiction_title_rule_num'])->text();
                    $title = trim($title);
                    $fiction_key = implode($pinyin->convert($title));
                    $author = $crawler->filter($rule['fiction_author_rule'])->eq($rule['fiction_author_rule_num'])->text();
                    $author = preg_replace('/\s*作.*?者\s*:?：?\s*/', '', $author);
                    $description = $crawler->filter($rule['fiction_description_rule'])->eq($rule['fiction_description_rule_num'])->text();
                    $cache = Yii::$app->cache;
                    $caption = $cache->get('ditch_'.$dk.'_fiction_'.$fiction_key.'_config');
                    if (!$caption){
                        $cache->set(
                            'ditch_'.$dk.'_fiction_'.$fiction_key.'_config',
                            [
                                'fiction_name' => $title,
                                'fiction_key' => $fiction_key,
                                'fiction_author' => $author,
                                'fiction_introduction' => $description,
                                'fiction_caption_url' => $url,
                                'fiction_caption_list_type' => 'current',
                                'fiction_caption_list_rule' => '#list dl dd a'
                            ],
                            Yii::$app->params['fiction_configure_cache_expire_time']
                        );
                    }
                    //获取小说章节列表
                    $list = $cache->get('ditch_' . $dk . '_fiction_detail' . $fiction_key . '_fiction_list');
                    if ($list === false || empty($list)) {
                        $list = [];
                        global $list;
                        $linkList = $crawler->filter($rule['fiction_caption_list_rule']);
                        $linkList->each(function($node) use ($list, $rule, $url){
                            global $list;
                            if ($node) {
                                $text = $node->text();
                                $href = $node->attr('href');
                                if ($rule['fiction_caption_list_type'] === 'current') {
                                    $href = rtrim($url, '/') . '/' . $href;
                                }
                                $list[] = ['url' => base64_encode($href), 'text' => $text];
                            }
                        });
                    }
                    $cache->set('ditch_' . $dk . '_fiction_detail' . $fiction_key . '_fiction_list', $list, Yii::$app->params['fiction_chapter_list_cache_expire_time']);
                    //返回小说详情
                    return [
                        'title' => $title,
                        'fiction_key' => $fiction_key,
                        'author' => $author,
                        'description' => $description,
                        'list' => $list,
                    ];
                } catch (Exception $e) {

                }
            }
        }
        return [];
    }
}