<?php

namespace console\controllers;


use common\models\Fiction;
use yii\console\Controller;

class GatherController extends Controller
{
    //更新所有渠道、所有分类的小说列表
    public function actionUpdateCategoryFiction()
    {
        try {
            Fiction::updateCategoryFictionList();
        } catch (\Exception $e) {
            $this->stdout($e->getMessage());
        }
    }
}