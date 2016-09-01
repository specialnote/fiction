<?php

namespace console\controllers;


use common\models\BaiDu\LinkPush;
use common\models\Fiction;
use yii\console\Controller;

class BaiduController extends Controller
{
    public function actionLinkPush()
    {
        $url = ['http://f.specialnote.cn/fic/detail?fid=8684&num=2'];
        $res = LinkPush::push($url);
        var_dump($res);die;
        $urls = Fiction::getAllFictionUrl();
        $count = count($urls);
        $totalPage = ceil($count / 1000);
        for ($i = 1; $i <= $totalPage; $i++) {
            $sliceUrls = array_slice($urls, ($i - 1) * 1000, 1000);
            $res = LinkPush::push($sliceUrls);
        }
    }
}