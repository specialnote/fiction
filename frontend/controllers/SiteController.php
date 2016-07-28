<?php

namespace frontend\controllers;

use common\models\Ditch;
use common\models\Fiction;
use frontend\models\Category;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        $fictions = Fiction::find()->where('fictionKey IS NOT NULL')->limit(12)->all();
        return $this->render('index',[
            'fictions' => $fictions,
        ]);
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
