<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Fiction;
use yii\data\Pagination;
use yii\web\Controller;

class CategoryController extends BaseController
{
    /**
     *分类小说列表页
     */
    public function actionIndex()
    {
        $dk = $this->get('dk');
        $dk = $dk ?: $this->ditch_key;
        $ck = $this->get('ck');
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

    //根据分类小说列表进入指定小说章节页面
    public function actionDetail() {
        $dk = $this->get('dk');
        $dk = $dk ?: $this->ditch_key;
        $url = base64_decode($this->get('url'));
        return $this->redirect('/fic/list?dk='.$dk.'&url='.base64_encode($url));
    }
}