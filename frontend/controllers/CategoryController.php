<?php

namespace frontend\controllers;

use common\models\Category;
use yii\data\Pagination;
use yii\web\Controller;

class CategoryController extends BaseController
{
    public function actionIndex()
    {
        $dk = $this->get('dk');
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
        $ck = $this->get('ck');
        $url = base64_decode($this->get('url'));
        $fiction = Category::getFictionCaptionList($url, $dk);
        if ($fiction) {
            return $this->redirect('/fic/list?dk='.$dk.'&fk='.$fiction['fiction_key'].'&url='.base64_encode($url));
        } else {
            $this->err404();
        }
    }
}