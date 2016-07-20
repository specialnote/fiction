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
    
    //更新指定小说的章节列表
    public function actionUpdateFictionChapterList()
    {
        Fiction::updateFictionChapterList();
    }
}
