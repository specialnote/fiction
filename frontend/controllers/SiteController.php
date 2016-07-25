<?php

namespace frontend\controllers;

use common\models\Ditch;
use frontend\models\Category;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
