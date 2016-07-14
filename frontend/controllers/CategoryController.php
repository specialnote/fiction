<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\Fiction;
use yii\base\Exception;
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
        //获取分类配置
        $category = Category::getDitchCategory($dk, $ck);
        //获取分类的小说列表
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