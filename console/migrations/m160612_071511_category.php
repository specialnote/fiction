<?php

use yii\db\Migration;

class m160612_071511_category extends Migration
{
    public function up()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'url' => $this->string(100),
            'categoryKey' => $this->string(32),
            'ditchKey' => $this->string(32),
            'categoryRule' => $this->string(50),//分类位置在页面中的选择器
            'categoryNum' => $this->integer(3),//分类位置在页面中的选择器序号(0开始)
            'fictionRule' => $this->string(50),//分类的所有小说列表在页面中的选择器
            'fictionLinkType' => $this->string(10),//章节列表链接类型。1表示current，是相对小说页面的相对地址；2表示home,即相对于渠道主页的地址
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%category}}');
        echo "m160612_071511_category cannot be reverted.\n";

        return false;
    }
}
