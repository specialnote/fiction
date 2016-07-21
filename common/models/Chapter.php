<?php

namespace common\models;

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
        if ($this->ditchKey && $this->fictionId && $this->tableName) {
            $sql = "SELECT chapter, url FROM " . $this->tableName . " ORDER BY id DESC";
            $list = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $list = [];
        }
        return $list;
    }

    //将章节列表保存到数据库
    public function updateFictionChapter($list)
    {
        if ($this->ditchKey && $this->fictionId && $this->tableName && $this->hasTable()) {
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
                $res = Yii::$app->db->createCommand()->batchInsert($this->tableName, ['ditchKey', 'fictionId', 'chapter', 'url'], $data)->execute();
                if ($res) {
                    return true;
                }
            }
        }
        return false;
    }
}
