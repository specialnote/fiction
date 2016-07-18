<?php

namespace console\controllers;

use common\models\Fiction;
use yii\console\Controller;

class FictionController extends Controller
{
    //定时任务，更新所有分类的小说信息
    public function actionUpdateFiction()
    {
        Fiction::updateCategoryFictionList();
    }
}