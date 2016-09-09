<?php

namespace console\controllers;

use common\models\Fiction;
use yii\console\Controller;

class GatherController extends Controller
{
    public function actionFictionImgUrl()
    {
        $fiction = Fiction::find()->one();
        $fiction->updateImgUrl();
    }
}
