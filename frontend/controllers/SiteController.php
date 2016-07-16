<?php
namespace frontend\controllers;

use frontend\models\Category;
use Yii;

class SiteController extends BaseController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dk = $this->ditch_key;
        $categoryList = Category::getDitchCategoryList($dk);
        return $this->render('index', [
            'categoryList' => $categoryList,
            'dk' => $dk,
        ]);
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
