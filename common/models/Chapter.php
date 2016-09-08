<?php

namespace common\models;

use common\models\BaiDu\LinkPush;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

class Chapter extends Model
{
    public $ditchKey;
    public $tableName;
    public $list;
    public $fictionId;

    public function initChapter(Fiction $fiction)
    {
        $this->ditchKey = $fiction->ditchKey;
        $this->fictionId = $fiction->id;
        $this->tableName = 'ditch_' . $this->ditchKey . '_fiction_' . $this->fictionId . '_chapter';
        return $this;
    }

    //动态添加表
    public function createTable()
    {
        if (!$this->hasTable() && $this->tableName) {
            $sql = "
CREATE TABLE IF NOT EXISTS " . $this->tableName . "(
	id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	ditchKey VARCHAR(50),
	fictionId INT(10),
	chapter VARCHAR(100),
	url VARCHAR(100)
)
";
            Yii::$app->db->createCommand($sql)->execute();
        }
    }

    //指定渠道已经创建表了
    public function hasTable()
    {
        $sql = "SHOW TABLES";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $table = ArrayHelper::getColumn($res, 'Tables_in_fiction');
        return in_array($this->tableName, $table);
    }

    //获取指定渠道指定小说的章节列表
    public function getList()
    {
        if ($this->hasTable()) {
            $sql = "SELECT chapter AS text, url FROM " . $this->tableName . " ORDER BY id ASC";
            $list = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $list = [];
        }
        return $list;
    }

    //将章节列表保存到数据库
    public function updateFictionChapter($list)
    {
        if ($this->hasTable()) {
            $id = Yii::$app->db->createCommand("SELECT MAX(id) FROM " . $this->tableName)->queryScalar();
            $id = intval($id);
            $data = [];
            //增量更新列表
            foreach ($list as $k => $v) {
                if ($k + 1 > $id) {
                    if ($v['text'] && $v['url']) {
                        $data[] = [$this->ditchKey, $this->fictionId, $v['text'], $v['url']];
                    }
                }
            }
            if ($data) {
                //更新章节信息
                $res = Yii::$app->db->createCommand()->batchInsert($this->tableName, ['ditchKey', 'fictionId', 'chapter', 'url'], $data)->execute();
                if ($res) {
                    return true;
                }
                if (YII_ENV === 'prod') {
                    //将更新的小说地址传到百度
                    $ids = [$this->fictionId];
                    $urls = Fiction::getFictionUrls($ids);
                    LinkPush::push($urls);
                }
            }
        }
        return false;
    }

    //根据章节编号超找指定的章节信息
    public function getChapter($num)
    {
        if ($this->hasTable()) {
            $sql = "SELECT chapter AS text, url FROM " . $this->tableName . " WHERE id = " . $num;
            return Yii::$app->db->createCommand($sql)->queryOne();
        } else {
            return [];
        }
    }

    //获取指定小说的最大章节
    public function getMaxNum()
    {
        if ($this->hasTable()) {
            $sql = "SELECT MAX(id) FROM " . $this->tableName;
            $res = Yii::$app->db->createCommand($sql)->queryScalar();
        } else {
            $res = 1;
        }
        return intval($res);
    }
}
