<?php

namespace console\controllers;


use common\models\Fiction;
use yii\console\Controller;

class DataController extends Controller
{
    public function actionUpdateHistoryImgUrl()
    {
        $fictions = Fiction::find()->where(['not', ['imgUrl' => null]])->all();
        $host = rtrim(\Yii::$app->params['frontend_host'], '/');
        foreach ($fictions as $fiction) {
            $url = $fiction->imgUrl;
            $headers = get_headers($url);
            if (!empty($headers)
                && !empty($headers[0])
                && strpos($headers[0], '200')
            ) {

            } else {
                $fiction->imgUrl = 'http://'.$host.'/images/default.svg';
                $fiction->save();
            }
        }
    }
}