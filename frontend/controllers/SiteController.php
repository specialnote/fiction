<?php

namespace frontend\controllers;

use common\models\Ditch;
use common\models\Fiction;
use frontend\models\Category;
use yii\helpers\ArrayHelper;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        $ids = Fiction::find()->select('id')->where('fictionKey IS NOT NULL')->all();
        $ids = ArrayHelper::getColumn($ids, 'id');
        $randIds = [];
        $count = count($ids);
        $maxNum = min(12, $count);
        while (count($randIds) < $maxNum) {
            $k = rand(0, $count - 1);
            if (isset($ids[$k]) && !in_array($ids[$k], $randIds)) {
                $randIds[] = $ids[$k];
            }
        }
        $fictions = Fiction::find()->where('fictionKey IS NOT NULL')->andWhere(['id' => $randIds])->all();
        return $this->render('index', [
            'fictions' => $fictions,
        ]);
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
