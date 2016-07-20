<?php

namespace common\models;


use yii\base\Model;
use Yii;

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
        if (!$this->hasTable && $this->tableName) {
            $sql = "
CREATE TABLE IF NOT EXISTS ".$this->tableName."(
	id INT(10) PRIMARY KEY,
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
        $sql = "SHOW DATABASES";
        $res = Yii::$app->db->createCommand($sql)->execute();
        var_dump($res);
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
        if ($this->ditchKey && $this->fictionKey && $this->tableName) {
            $data = "";
            foreach ($list as $k => $v) {
                if ($v['text'] && $v['url']) {
                    $data .= "('" . ($k + 1) . "','" . $v['text'] . "','" . $v['ur'] . "''),";
                }
            }
            $data = rtrim($data, ',');
            $sql = "INSERT INTO :tableName (listNum, chapter, url) VALUES :data";
            $res = Yii::$app->db->createCommand($sql, ['tableName' => $this->tableName, 'data' => $data])->execute();
            if ($res) {
                return true;
            }
        }
        return false;
    }
}