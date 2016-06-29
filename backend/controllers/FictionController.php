<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;

class FictionController extends Controller
{
    public function actionIndex(){
        $ditches = Yii::$app->params['ditch'];

        return $this->render('index', [
            'ditches' => (!empty($ditches) && is_array($ditches)) ? $ditches : [],
        ]);
    }
}