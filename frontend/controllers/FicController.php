<?php

namespace frontend\controllers;

use common\models\BaiDu\LinkPush;
use common\models\Chapter;
use common\models\Fiction;
use common\models\UpdateUrl;
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
        Fiction::updateAll(['views' => ($fiction->views + 1)], ['id' => $fiction->id]);
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
            $host = rtrim(\Yii::$app->params['frontend_host'], '/');
            $url = 'http://'.$host . '/fic/index?id=' . $fiction->id;
            $date = date('Y-m-d');
            $updateLog = UpdateUrl::find()->where(['url' => $url, 'updateTime' => $date])->one();
            if (!$updateLog) {
                $fiction->updateFictionDetail();
                $log = new UpdateUrl([
                    'url' => $url,
                    'updateTime' => $date,
                ]);
                $log->save();
            }
        }
    }

    //获取章节详情
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

    //缓存指定小说、指定章节详情
    public function actionCache($fid, $num)
    {
        $fiction = Fiction::findOne($fid);
        if ($fiction) {
            $res = $fiction->cache($num);
            if (YII_ENV === 'prod' && $res) {
                $data = [['id' => $fid, 'num' => $num]];
                //向百度推送小说详情章节
                $urls = Fiction::getDetailUrls($data);
                $result = LinkPush::push($urls);
                Yii::trace('百度链接提交-地址：'.json_encode($urls).';提交结果：'.$result, 'baidu');
            }
            return $res;
        }
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
