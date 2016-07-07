<?php

namespace frontend\controllers;

use common\models\Category;
use yii\data\Pagination;
use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionIndex($dk, $ck)
    {
        $categoryList = Category::getDitchCategoryList($dk);
        $category = Category::getDitchCategory($dk, $ck);
        $fictionList = Category::getDitchCategoryFictionList($dk, $ck);
        $pages = new Pagination([
            'totalCount' => count($fictionList),
            'pageSize' => 100,
        ]);
        $fictionList = array_slice($fictionList, $pages->offset, $pages->pageSize);
        return $this->render('index', [
           'fictionList' => $fictionList,
            'dk' => $dk,
            'ck' => $ck,
            'category' => $category,
            'pages' => $pages,
            'categoryList' => $categoryList,
        ]);
    }

    public function actionDetail($dk, $ck, $url) {

    }
}