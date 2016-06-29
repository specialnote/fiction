<?php

namespace backend\models;

use Goutte\Client;
use yii\base\Exception;
use yii\base\Model;
use Yii;

class Fiction extends Model
{
    /**
     * 判断指定渠道的指定小说的章节列表能够采集
     * 判断逻辑：是否能够采集到列表，列表长度大于0
     * @param string $ditch_key 渠道key
     * @param string $fiction_key 小说key
     * @return integer
     */
    public static function isFictionRunning($ditch_key, $fiction_key)
    {
        if (isset(Yii::$app->params['ditch'][$ditch_key]['fiction_list'][$fiction_key])) {
            $fiction = Yii::$app->params['ditch'][$ditch_key]['fiction_list'][$fiction_key];
            if ($fiction) {
                $client = new Client();
                $crawler = $client->request('GET', $fiction['fiction_url']);
                try {
                    if ($crawler) {
                        $a = $crawler->filter($fiction['fiction_list_rule']);
                        if ($a && count($a) > 0) {
                            $href = $a->eq(0)->attr('href');
                            if ($href) {
                               if ($fiction['fiction_list_type'] == 'current') {
                                   $url = rtrim($fiction['fiction_url'], '/') . '/' . $href;
                               } else {
                                   //todo 其他渠道不同情况处理
                                   $url = $href;
                               }
                               $crawler = $client->request('GET', $url);
                               if ($crawler) {
                                   $content = $crawler->filter($fiction['fiction_detail_rule']);
                                   if ($content && $content->text()) {
                                       return 20;
                                   }
                               }
                           }
                           return 10;
                        }
                    }
                } catch (Exception $e) {
                }
            }
        }
        return 0;
    }

    public static function getFictionList($ditch_key, $fiction_key) {
        $array = [];
        if (isset(Yii::$app->params['ditch'][$ditch_key]['fiction_list'][$fiction_key])) {
            $fiction = Yii::$app->params['ditch'][$ditch_key]['fiction_list'][$fiction_key];
            if ($fiction) {
                $client = new Client();
                $crawler = $client->request('GET', $fiction['fiction_url']);
                try {
                    if ($crawler) {
                        $a = $crawler->filter($fiction['fiction_list_rule']);
                        if ($a && count($a) > 0) {
                            global $array;
                            $a->each(function($node) use($array, $fiction){
                                global $array;
                                if ($node) {
                                    $href = $node->attr('href');
                                    if ($href) {
                                        if ($fiction['fiction_list_type'] == 'current') {
                                            $url = rtrim($fiction['fiction_url'], '/') . '/' . $href;
                                        } else {
                                            $url = $href;
                                        }
                                    }
                                    $text = $node->text();
                                    $array[] = ['href' => $url, 'text' => $text];
                                }
                            });
                        }
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
        return $array;
    }
}