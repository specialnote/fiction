<?php

namespace frontend\controllers;

use backend\models\Fiction;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\helpers\Html;
use Yii;
use yii\web\NotFoundHttpException;

class FicController extends BaseController
{
    public function actionList($dk, $fk) {
        $dk = Html::encode($dk);
        $fk = Html::encode($fk);
        if (isset(Yii::$app->params['ditch'][$dk]['fiction_list'][$fk])) {
            $fiction = Yii::$app->params['ditch'][$dk]['fiction_list'][$fk];
            $list = Fiction::getFictionList($dk, $fk);
            return $this->render('list',[
                'fiction' => $fiction,
                'list' => $list,
            ]);
        } else {
            $this->err404('页面未找到');
        }
    }
}