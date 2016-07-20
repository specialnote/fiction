<?php

namespace common\models;

use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

class Chapter extends Model
{
    public $ditchKey;
    public $fictionKey;
    public $tableName;
    public $list;
    public $fictionId;

    public function initChapter(Fiction $fiction)
    {
        $this->ditchKey = $fiction->ditchKey;
        $this->fictionKey = $fiction->fictionKey;
        $this->fictionId = $fiction->id;
        $this->tableName = 'chapter_' . $this->ditchKey . '_' . $this->fictionKey;
        return $this;
    }

    //动态添加表
    public function createTable()
    {
        if (!$this->hasTable() && $this->tableName) {
            $sql = "
CREATE TABLE IF NOT EXISTS ".$this->tableName."(
	id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	ditchKey VARCHAR(50),
	fictionKey VARCHAR(50),
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
        if ($this->ditchKey && $this->fictionKey && $this->tableName) {
            $sql = "SELECT chapter, url FROM :tableName ORDER BY id DESC";
            $list = Yii::$app->db->createCommand($sql, ['tableName', $this->tableName])->queryAll();
        } else {
            $list = [];
        }
        return $list;
    }

    //将章节列表保存到数据库
    public function updateFictionChapter($list)
    {
        if ($this->ditchKey && $this->fictionKey && $this->tableName && $this->hasTable()) {
            $data = "";
            foreach ($list as $k => $v) {
                if ($v['text'] && $v['url']) {
                    $data .= "('" . $this->ditchKey . "','".$this->fictionKey."','". $v['text'] . "','" . $v['url'] . "'),";
                }
            }
            $data = rtrim($data, ',');
            $sql = "INSERT INTO ".$this->tableName." (ditchKey, fictionKey, chapter, url) VALUES $data";
            $res = Yii::$app->db->createCommand($sql)->execute();
            if ($res) {
                return true;
            }
        }
        return false;
    }
}
