<?php

namespace common\models;


use yii\base\Model;

class Chapter extends Model
{
    public $ditchKey;
    public $fictionKey;
    public $tableName;
    public $list;
    public $fictionId;

    private function initChapter(Fiction $fiction)
    {
        $this->ditchKey = $fiction->ditchKey;
        $this->fictionKey = $fiction->fictionKey;
        $this->fictionId = $fiction->id;
        $this->tableName = $this->ditchKey.'_'.$this->fictionKey;
        $this->createTable();
        return $this;
    }

    private function createTable()
    {
        //todo 新建数据表
    }

    public function getList()
    {
        //todo 从数据库中获取所有列表
        $list = [];
        $this->list = $list;
        return $this->list;
    }

   public function findById($fictionId,$chapterId)
   {
        //todo 从数据库获取数据
       //todo 返回对象
   }

    public function updateFictionChapter($list)
    {
        //todo 更新章节列表 根据数据库中记录数
    }
}