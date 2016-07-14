<?php

namespace console\controllers;

use common\models\Ditch;
use yii\console\Controller;

class DitchController extends Controller
{
    public function actionInitDitch()
    {
        Ditch::updateDitchInformation();
    }
}