<?php

namespace frontend\controllers;


use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{
    public function err404($message = ''){
        throw new NotFoundHttpException($message);
    }

    public function get($key){
        if ($key && is_string($key)) {
            $content = \Yii::$app->request->get($key);
            return Html::encode($content);
        }
        return null;
    }
}