<?php

namespace console\controllers;

use common\models\Fiction;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class FictionController extends Controller
{
    //定时任务，更新所有分类的小说信息，将小说信息缓存
    public function actionUpdateFiction()
    {
        @ini_set('memory_limit', '256M');
        Fiction::updateCategoryFictionList();
    }

    //更新所有小说的章节列表
    public function actionUpdateFictionChapterList()
    {
        @ini_set('memory_limit', '256M');
        $fictions = Fiction::find()->where(['status' => 1])->andWhere(['fictionKey' => null])->orderBy(['id' => SORT_ASC])->limit(25)->all();
        if (count($fictions) > 0) {
            foreach ($fictions as $fiction) {
                if (!$fiction->fictionKey) {
                    $fiction->updateFictionDetail();
                }
            }
        }
        //将异常小说标出来
        $exceptionFiction =  Fiction::find()->select(['id'])->where(['status' => 1])->andWhere(['fictionKey' => null])->andWhere(['id' => ArrayHelper::getColumn($fictions, 'id')])->asArray()->all();
        if (count($exceptionFiction) > 0) {
            Fiction::updateAll(['status' => 2], ['id' => ArrayHelper::getColumn($exceptionFiction, 'id')]);
        }
    }

    //更新指定小说的章节信息
    public function actionUpdateFictionChapterListById($id)
    {
        $fiction = Fiction::findOne($id);
        if (!$fiction->fictionKey) {
            $fiction->updateFictionDetail();
        }
    }

    public function actionUpdateImgUrl()
    {
        $fictions = Fiction::find()->where(['not', ['fictionKey' => null]])->andWhere(['imgUrl' => null])->andWhere(['status' => 1])->andWhere(['>', 'views', 0])->limit(10)->orderBy(['views' => SORT_DESC])->all();
        if ($fictions) {
            foreach ($fictions as $fiction) {
                $fiction->updateImgUrl();
            }
        }
    }
}
