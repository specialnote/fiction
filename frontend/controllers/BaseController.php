<?php

namespace frontend\controllers;


use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{
    public function err404($message = ''){
        throw new NotFoundHttpException($message);
    }
}