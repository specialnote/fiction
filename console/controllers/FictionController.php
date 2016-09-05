<?php

namespace console\controllers;

use common\models\Fiction;
use yii\console\Controller;

class FictionController extends Controller
{
    //定时任务，更新所有分类的小说信息，将小说信息缓存
    public function actionUpdateFiction()
    {
        @ini_set('memory_limit', '256M');
        Fiction::updateCategoryFictionList();
    }

    //更新所有小说的章节列表
    public function actionUpdateFictionChapterList($page = 1)
    {
        @ini_set('memory_limit', '256M');
        $fictions = Fiction::find()->where(['status' => 1])->offset(($page - 1) * 100)->limit(100)->orderBy(['id' => SORT_ASC])->all();
        foreach ($fictions as $fiction) {
            $fictions->updateFictionDetail();
        }
    }
}
