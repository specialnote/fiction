<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Fiction;
use yii\data\Pagination;

class CategoryController extends BaseController
{
    /**
     *分类小说列表页.
     */
    public function actionIndex($id)
    {
        $category = Category::findOne($id);
        $query = Fiction::find()->where(['ditchKey' => $this->ditchKey, 'categoryKey' => $category->categoryKey]);
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 40
        ]);
        $fictionList = $query->offset($pages->offset)->limit($pages->limit)->orderBy(['views' => SORT_DESC])->all();
        return $this->render('index', [
            'category' => $category,
            'fictionList' => $fictionList,
            'pages' => $pages,
        ]);
    }
}
