<?php

namespace common\models;
use Yii;
use Goutte\Client;
use yii\base\Exception;


class Gather
{
    //采集指定分类的小说列表
    public static function getDitchCategoryFictionList($ditch_key, $category_key)
    {
        if (isset(Yii::$app->params['ditch'][$ditch_key]['category_list'][$category_key])) {
            $homePage = Yii::$app->params['ditch'][$ditch_key]['ditch_domain'];
            $category = Yii::$app->params['ditch'][$ditch_key]['category_list'][$category_key];
            if ($category && is_array($category['category_url']) && count($category['category_url']) > 0) {
                $client = new Client();
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
               return $list;
            }
        }
        return [];
    }
}