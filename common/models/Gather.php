<?php

namespace common\models;

use Goutte\Client;

class Gather
{
    /**
     * @param string $url          分类所在页面地址
     * @param string $categoryRule 分类所在区块的采集规则
     * @param int    $categoryNum  根据分类规则获取分类的序号
     * @param string $fictionRule  分类中小说列表的规则
     * @param string $preUrl       小说地址的前缀
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
                                $href = rtrim($preUrl, '/').'/'.$href;
                            }
                            $list[] = ['url' => $href, 'text' => $text];
                        }
                    }
                });
            }
        } catch (\Exception $e) {
            //todo 采集失败 记录日志
        }

        return $list;
    }
}
