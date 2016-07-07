<?php
namespace frontend\controllers;

use common\models\Category;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dk = 'biquge';
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
