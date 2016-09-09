<?php

namespace common\models;

use Goutte\Client;

class Gather
{
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
        $client = new Client();
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
        $client = new Client();
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
        $client = new Client();
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
        $client = new Client();
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
