<?php

namespace frontend\controllers;

use common\models\Chapter;
use common\models\Fiction;
use Goutte\Client;
use yii\base\Exception;
use yii\helpers\Html;
use Yii;

class FicController extends BaseController
{
    public function actionIndex($id)
    {
        $fiction = Fiction::findOne($id);
        if (!$fiction) {
            $this->err404('没有找到指定小说');
        }
        if (!$fiction->author || !$fiction->description || !$fiction->getChapterList()) {
            $fiction = $fiction->getFunctionDetail();
        }
        if (!$fiction->author || !$fiction->description) {
            $this->err404('没有找到指定小说数据');
        }
        $chapterList = $fiction->getChapterList();
        return $this->render('index', [
            'fiction' => $fiction,
            'chapterList' => $chapterList,
        ]);
    }

    //更新章节列表
    public function actionUpdate($id)
    {
        $fiction = Fiction::findOne($id);
        if ($fiction) {
            $fiction->updateFictionDetail();
        }
    }

    public function actionDetail($fid, $num)
    {
        $fiction = Fiction::findOne($fid);
        if (!$fiction) {
            $this->err404('没有找到指定小说');
        }
        $detail = $fiction->getDetail($num);
        return $this->render('detail', [
            'fiction' => $fiction,
            'detail' => $detail,
            'num' => $num,
        ]);
    }

    //ajax获取上一章、下一章
    public function actionPn($fid, $num)
    {
        $fiction = Fiction::findOne($fid);
        if (!$fiction) {
            $this->err404('没有找到指定小说');
        }
        $chapter = (new Chapter())->initChapter($fiction);
        $max = $chapter->getMaxNum();
        return ['prev' => max(1, $num - 1), 'next' => min($max, $num + 1)];
    }
}
