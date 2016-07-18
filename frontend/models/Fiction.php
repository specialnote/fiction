<?php

namespace frontend\models;

use Goutte\Client;
use yii\base\Exception;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

class Fiction extends Model
{
    /**
     * 判断指定渠道的指定小说的章节列表能够采集
     * 判断逻辑：是否能够采集到列表，列表长度大于0.
     *
     * @param string $ditch_key   渠道key
     * @param string $fiction_key 小说key
     *
     * @return int
     */
    public static function isFictionRunning($ditch_key, $fiction_key)
    {
        $fiction = self::getFiction($ditch_key, $fiction_key);
        if ($fiction) {
            $client = new Client();
            $crawler = $client->request('GET', $fiction['fiction_caption_url']);
            try {
                if ($crawler) {
                    $a = $crawler->filter($fiction['fiction_caption_list_rule']);
                    if ($a && count($a) > 0) {
                        $href = $a->eq(0)->attr('href');
                        if ($href) {
                            if ($fiction['fiction_caption_list_type'] == 'current') {
                                $url = rtrim($fiction['fiction_caption_url'], '/').'/'.$href;
                            } else {
                                //todo 其他渠道不同情况处理
                                $url = $href;
                            }
                            $crawler = $client->request('GET', $url);
                            if ($crawler) {
                                $detail = $crawler->filter($fiction['fiction_detail_rule']);
                                $content = $detail->eq(0);
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

        return 0;
    }

    /**
     * 获取小说的章节列表.
     *
     * @param $ditch_key
     * @param $fiction_key
     *
     * @return array
     */
    public static function getFictionList($ditch_key, $fiction_key)
    {
        $array = [];
        $fiction = self::getFiction($ditch_key, $fiction_key);
        if ($fiction) {
            $cache = Yii::$app->cache;
            $list = $cache->get('ditch_'.$ditch_key.'_fiction_detail'.$fiction_key.'_fiction_list');
            if ($list === false || empty($list)) {
                $client = new Client();
                $crawler = $client->request('GET', $fiction['fiction_caption_url']);
                try {
                    if ($crawler) {
                        $a = $crawler->filter($fiction['fiction_caption_list_rule']);
                        if ($a && count($a) > 0) {
                            global $array;
                            $a->each(function ($node) use ($array, $fiction) {
                                global $array;
                                if ($node) {
                                    $href = $node->attr('href');
                                    if ($fiction['fiction_caption_list_type'] == 'current') {
                                        $url = base64_encode(rtrim($fiction['fiction_caption_url'], '/').'/'.$href);
                                    } else {
                                        $url = $href;
                                    }
                                    $text = $node->text();
                                    $array[] = ['url' => $url, 'text' => $text];
                                }
                            });
                        }
                    }
                } catch (Exception $e) {
                    //todo
                }
                $cache->set('ditch_'.$ditch_key.'_fiction_detail'.$fiction_key.'_fiction_list', $array, Yii::$app->params['fiction_chapter_list_cache_expire_time']);
            } else {
                $array = $list;
            }
        }

        return $array;
    }

    /**
     * 获取指定小说指定章节上上一章、下一章url.
     *
     * @param $ditch_key
     * @param $fiction_key
     * @param $url
     *
     * @return array
     */
    public static function getPrevAndNext($ditch_key, $fiction_key, $url)
    {
        $list = self::getFictionList($ditch_key, $fiction_key);
        $urls = ArrayHelper::getColumn($list, 'url');
        if (in_array(base64_encode($url), $urls)) {
            $current = array_search(base64_encode($url), $urls);
        } else {
            $current = false;
        }
        if ($current !== false) {
            return [
                'prev' => ($current - 1 >= 0) ? $list[$current - 1]['url'] : false,
                'next' => ($current + 1 < count($list) - 1) ? $list[$current + 1]['url'] : false,
            ];
        } else {
            return [
                'prev' => false,
                'next' => false,
            ];
        }
    }

    /**
     * 获取指定章节的title和序号.
     *
     * @param $ditch_key
     * @param $fiction_key
     * @param $url
     *
     * @return array
     */
    public static function getFictionTitleAndNum($ditch_key, $fiction_key, $url)
    {
        $list = self::getFictionList($ditch_key, $fiction_key);
        $urls = ArrayHelper::getColumn($list, 'url');
        if (in_array(base64_encode($url), $urls)) {
            $current = array_search(base64_encode($url), $urls);
        } else {
            $current = false;
        }
        if ($current) {
            $title = $list[$current]['text'];
        } else {
            $title = '';
        }

        return ['title' => $title, 'current' => intval($current)];
    }

    /**
     * 返回指定小说的配置
     * 如果没有找到，则返回null.
     *
     * @param $ditch_key
     * @param $fiction_key
     * @param $url
     *
     * @return null|array
     */
    public static function getFiction($ditch_key, $fiction_key, $url = null)
    {
        //根据分类从列表获取url读取小说信息 2.0
        $cache = Yii::$app->cache;
        $fiction = $cache->get('ditch_'.$ditch_key.'_fiction_'.$fiction_key.'_config');
        if (!$fiction) {
            $fiction = self::getFictionByUrl($ditch_key, $url);
            if ($fiction) {
                $cache->set(
                    'ditch_'.$ditch_key.'_fiction_'.$fiction_key.'_config',
                    $fiction,
                    Yii::$app->params['fiction_configure_cache_expire_time']
                );

                return $fiction;
            }
        } else {
            return $fiction;
        }

        return [];
    }

    /**
     * 根据小说url获取小说信息并缓存.
     *
     * @param $ditch_key
     * @param $url
     *
     * @return array
     */
    public static function getFictionInformationByUrl($ditch_key, $url)
    {
        $cache = Yii::$app->cache;
        $fictionInformation = $cache->get('ditch_'.$ditch_key.'_fiction_'.$url.'_config');
        if (!$fictionInformation) {
            $fictionInformationCaptionList = Gather::getFictionInformationAndCaptionList($ditch_key, $url);
            $fictionInformation = $fictionInformationCaptionList['fictionInformation'];
            $cache->set(
                'ditch_'.$ditch_key.'_fiction_'.$fictionInformation['fiction_key'].'_config', $fictionInformation, Yii::$app->params['fiction_configure_cache_expire_time']
            );
            $cache->set(
                'ditch_'.$ditch_key.'_fiction_'.$url.'_config', $fictionInformation, Yii::$app->params['fiction_configure_cache_expire_time']
            );
        }

        return $fictionInformation;
    }
}
