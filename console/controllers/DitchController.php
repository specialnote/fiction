<?php

namespace console\controllers;

use common\models\Ditch;
use yii\console\Controller;
use yii\helpers\Console;

class DitchController extends Controller
{
    /**
     * 更新渠道信息.
     *
     * @throws \Exception
     */
    public function actionUpdateDitch()
    {
        try {
            Ditch::updateDitchInformation();
        } catch (\Exception $e) {
            $this->stdout($e->getMessage(), Console::BG_RED);
        }
    }
}
