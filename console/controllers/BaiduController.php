<?php

namespace console\controllers;


use common\models\BaiDu\LinkPush;
use common\models\Fiction;
use yii\console\Controller;

class BaiduController extends Controller
{
    //向百度推送链接
    public function actionLinkPush()
    {
        $urls = Fiction::getAllFictionUrl();
        $count = count($urls);
        $totalPage = ceil($count / 1000);
        for ($i = 1; $i <= $totalPage; $i++) {
            $sliceUrls = array_slice($urls, ($i - 1) * 1000, 1000);
            $res = LinkPush::push($sliceUrls);
            var_dump($res);
        }
    }

    //生成百度sitemap
    public function actionSitemap()
    {
        $mapString = <<<STRING
<?xml version="1.0" encoding="UTF-8" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">
STRING;
        $urls = Fiction::getAllFictionUrl();
        foreach ($urls as $url) {
            $date = date('Y-m-d');
            $mapString = $mapString . "<url>\n<loc>$url</loc>\n<mobile:mobile type=\"pc,mobile\"/>\n<lastmod>$date</lastmod>\n<changefreq>daily</changefreq>\n<priority>0.8</priority>\n</url>\n";
        }
        $mapString = $mapString . "</urlset>";
        file_put_contents(__DIR__ . "/../../frontend/web/sitemap_main.xml", $mapString);
    }
}