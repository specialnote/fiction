<?php

namespace common\models;

use Goutte\Client;

class Gather
{
    public static function getClient(){
        $client = new Client();
        $ip = self::Rand_IP();
        $client->setServerParameters([
            'Accept' => 'text/html',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36',
            'CLIENT-IP' => $ip,
            'X-FORWARDED-FOR' => $ip,
        ]);
        return $client;
    }
    public static function  Rand_IP(){

        $ip2id= round(rand(600000, 2550000) / 10000); //第一种方法，直接生成
        $ip3id= round(rand(600000, 2550000) / 10000);
        $ip4id= round(rand(600000, 2550000) / 10000);
        //下面是第二种方法，在以下数据中随机抽取
        $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
        $randarr= mt_rand(0,count($arr_1)-1);
        $ip1id = $arr_1[$randarr];
        return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
    }
    /**
     * 采集章节列表
     * @param string $url 分类所在页面地址
     * @param string $categoryRule 分类所在区块的采集规则
     * @param int $categoryNum 根据分类规则获取分类的序号
     * @param string $fictionRule 分类中小说列表的规则
     * @param string $preUrl 小说地址的前缀
     *
     * @return array
     */
    public static function gatherCategoryFictionList($url, $categoryRule, $fictionRule, $categoryNum = 0, $preUrl = '')
    {
        $client = self::getClient();
        $list = [];
        try {
            $crawler = $client->request('GET', $url);
            if ($crawler) {
                $c = $crawler->filter($categoryRule);
                $c = $c->eq($categoryNum);
                global $list;
                $c->filter($fictionRule)->each(function ($node) use ($list, $preUrl) {
                    global $list;
                    if ($node) {
                        $text = $node->text();
                        $href = $node->attr('href');
                        if ($text && $href) {
                            if ($preUrl) {
                                $href = rtrim($preUrl, '/') . '/' . $href;
                            }
                            $list[] = ['url' => $href, 'text' => $text];
                        }
                    }
                });
            }
        } catch (\Exception $e) {
            $log = new Log([
                'type' => Log::LOG_TYPE_GATHER,
                'model' => Gather::class,
                'function' => __FUNCTION__,
                'work' => '采集小说列表',
                'note' => $url,
            ]);
            $log->save();
        }

        return $list;
    }

    //采集指定小说的 章节列表 以及 小说信息
    public static function getFictionInformationAndChapterList($url, Ditch $ditch, $refUrl = '', $getList = true, $getInfo = true)
    {
        $client = self::getClient();
        if ($url) {
            $crawler = $client->request('GET', $url);
            try {
                if ($getInfo) {
                    //获取小说信息
                    $author = $crawler->filter($ditch->authorRule)->eq($ditch->authorNum)->text();
                    $author = preg_replace('/\s*作.*?者\s*:?：?\s*/', '', $author);
                    $author = trim($author);
                    $description = $crawler->filter($ditch->descriptionRule)->eq($ditch->descriptionNum)->text();
                    $description = trim($description);
                }
                if ($getList) {
                    //获取小说章节列表
                    $list = [];
                    global $list;
                    $linkList = $crawler->filter($ditch->chapterRule);
                    $linkList->each(function ($node) use ($list, $refUrl) {
                        global $list;
                        if ($node) {
                            $text = $node->text();
                            $href = $node->attr('href');
                            if ($refUrl) {
                                $href = rtrim($refUrl, '/') . '/' . $href;
                            }
                            $list[] = ['url' => $href, 'text' => $text];
                        }
                    });
                }
            } catch (\Exception $e) {
                $log = new Log([
                    'type' => Log::LOG_TYPE_GATHER,
                    'model' => Gather::class,
                    'function' => __FUNCTION__,
                    'work' => '采集章节列表',
                    'note' => $url,
                ]);
                $log->save();
            }
        }

        return [
            'ditchKey' => $ditch->ditchKey,
            'author' => isset($author) ? $author : '',
            'description' => isset($description) ? $description : '',
            'list' => isset($list) ? $list : [],
        ];
    }

    //采集小说详情
    public static function getFictionDetail($url, $rule)
    {
        $content = '';
        $client = self::getClient();
        $crawler = $client->request('GET', $url);
        try {
            if ($crawler) {
                $detail = $crawler->filter($rule);
                if ($detail) {
                    $text = $detail->html();
                    $text = preg_replace('/<script.*?>.*?<\/script>/', '', $text);
                    $text = preg_replace('/(<br\s?\/?>){1,}/', '<br/><br/>', $text);
                    $text = strip_tags($text, '<p><div><br>');
                    $content = $content . $text;
                }
            }
        } catch (\Exception $e) {
            $log = new Log([
                'type' => Log::LOG_TYPE_GATHER,
                'model' => Gather::class,
                'function' => __FUNCTION__,
                'work' => '采集章节详情',
                'note' => $url,
            ]);
            $log->save();
        }
        return $content;
    }

    //采集指定小说的图片地址
    public static function getFictionImgUrl($fictionUrl)
    {
        $client = self::getClient();
        $crawler = $client->request('GET', $fictionUrl);
        try {
            if ($crawler) {
                $img = $crawler->filter('#fmimg img');
                if ($img) {
                    return $img->attr('src');
                }
            }
        } catch (\Exception $e) {
            $log = new Log([
                'type' => Log::LOG_TYPE_GATHER,
                'model' => Gather::class,
                'function' => __FUNCTION__,
                'work' => '采集小说图片',
                'note' => $fictionUrl,
            ]);
            $log->save();
        }
        return null;
    }
}
