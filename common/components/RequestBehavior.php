<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\Response;

class RequestBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $headers = Yii::$app->request->headers;
            $accept = $headers->get('Accept');
            if ('text/html' !== $accept) {
                Yii::$app->response->format = Response::FORMAT_JSON;
            }
        }
    }
}
