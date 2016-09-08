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

    public function actionSearch()
    {
        $key = trim(\Yii::$app->request->get('search_key', ''));
        if ($key) {
            //查找小说名
            $query = Fiction::find()->where(['like', 'name', $key]);
            $pages = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => 40
            ]);
            $fictionList = $query->offset($pages->offset)->limit($pages->limit)->orderBy(['views' => SORT_DESC])->all();
            if (count($fictionList) > 0) {
                $fiction = $fictionList[0];
                $category = $fiction->category;
            } else {
                $query = Fiction::find()->where(['like', 'author', $key]);
                $pages = new Pagination([
                    'totalCount' => $query->count(),
                    'pageSize' => 40
                ]);
                $fictionList = $query->offset($pages->offset)->limit($pages->limit)->orderBy(['views' => SORT_DESC])->all();
                if (count($fictionList) > 0) {
                    $fiction = $fictionList[0];
                    $category = $fiction->category;
                }
            }
            if (!isset($category)) {
                $category = Category::find()->one();
            }
            return $this->render('index', [
                'category' => $category,
                'fictionList' => $fictionList,
                'pages' => $pages,
            ]);
        }
        $this->err404('搜索参数错误');
    }
}
