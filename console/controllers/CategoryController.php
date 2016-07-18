<?php

namespace console\controllers;


use common\models\Category;
use yii\console\Controller;
use yii\helpers\Console;

class CategoryController extends Controller
{
    /**
     * 更新分类信息
     */
    public function actionUpdateCategory()
    {
        try{
            Category::updateCategoryInformation();
        }catch(\Exception $e){
            $this->stdout($e->getMessage(),Console::BG_RED);
        }
    }
}