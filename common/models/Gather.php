<?php

namespace common\models;
use Overtrue\Pinyin\Pinyin;
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

    //采集指定小说的 章节列表 以及 小说信息
    public static function getFictionInformationAndCaptionList($url, $ditch_key)
    {
        $ditch = new Ditch($ditch_key);
        $rule = $ditch->getFictionCaptionListRule();//获取章节列表采集规则
        if ($ditch && $rule) {
            $client = new Client();
            if ($url) {
                $crawler = $client->request('GET', $url);
                try {
                    //获取小说信息
                    $pinyin = new Pinyin();
                    $title = $crawler->filter($rule['fiction_title_rule'])->eq($rule['fiction_title_rule_num'])->text();
                    $title = trim($title);
                    $fiction_key = implode($pinyin->convert($title));//小说名称的全拼作为小说的fiction_key
                    $author = $crawler->filter($rule['fiction_author_rule'])->eq($rule['fiction_author_rule_num'])->text();
                    $author = preg_replace('/\s*作.*?者\s*:?：?\s*/', '', $author);
                    $description = $crawler->filter($rule['fiction_description_rule'])->eq($rule['fiction_description_rule_num'])->text();
                    $fiction_information =  [
                        'fiction_name' => $title,
                        'fiction_key' => $fiction_key,
                        'fiction_author' => $author,
                        'fiction_introduction' => $description,
                        'fiction_caption_url' => $url,
                        'fiction_caption_list_type' => 'current',
                        'fiction_caption_list_rule' => '#list dl dd a'
                    ];

                    //获取小说章节列表
                    $list = [];
                    global $list;
                    $linkList = $crawler->filter($rule['fiction_caption_list_rule']);
                    $linkList->each(function ($node) use ($list, $rule, $url) {
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
                    //返回小说详情
                   $fiction_caption_list = $list;
                } catch (Exception $e) {

                }
            }
        }
        return [
            'fiction_information' => isset($fiction_information) ? $fiction_information : [],
            'fiction_caption_list' => isset($fiction_caption_list) ? $fiction_caption_list : [],
        ];
    }
}