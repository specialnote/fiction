<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

class MailController extends Controller
{
    public function actionTest()
    {
        $mail = Yii::$app->mailer->compose();
        $mail = Yii::$app->mailer->compose(['html' => 'ceshi'], ['key' => '测试模板']);
        $mail->setTo('shiyang@wangcaigu.com');
        $mail->setSubject('测试Yii2邮件发送');
        $mail->setHtmlBody('邮件内容，这里可以使用 HTML 代码');
        $mail->send();//发送
    }
}
