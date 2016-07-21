<?php

namespace console\controllers;

use common\models\Fiction;
use yii\console\Controller;

class FictionController extends Controller
{
    //定时任务，更新所有分类的小说信息，将小说信息缓存
    public function actionUpdateFiction()
    {
        Fiction::updateCategoryFictionList();
    }

    //根据缓存信息更新数据库
    public function actionUpdateFictionWithCache()
    {
        Fiction::updateFictionWithCache();
    }

    //更新指定小说的章节列表
    public function actionUpdateFictionChapterList()
    {
        @ini_set('memory_limit', '256M');
        $count = Fiction::find()->where(['status' => 1])->count();
        if ($count > 0) {
            for ($i = 1; $i <= ceil($count / 10); $i++) {
                Fiction::updateFictionChapterList($i * 10);
            }
        }

    }


}
