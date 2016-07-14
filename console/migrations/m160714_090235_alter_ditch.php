<?php

use yii\db\Migration;

class m160714_090235_alter_ditch extends Migration
{
    public function up()
    {
        $this->addColumn('{{%ditch}}', 'titleRule', $this->string(30));//小说名规则
        $this->addColumn('{{%ditch}}', 'titleNum', $this->integer(1)->defaultValue(0));//小说名dom顺序，表示第几个相应的rule
        $this->addColumn('{{%ditch}}', 'authorRule', $this->string(30));//小说作者规则
        $this->addColumn('{{%ditch}}', 'authorNum', $this->integer(1)->defaultValue(0));//作者dom顺序
        $this->addColumn('{{%ditch}}', 'descriptionRule', $this->string(30));
        $this->addColumn('{{%ditch}}', 'descriptionNum', $this->integer(1)->defaultValue(0));
        $this->addColumn('{{%ditch}}', 'captionRule', $this->string(30));
        $this->addColumn('{{%ditch}}', 'captionLinkType', $this->integer(1)->defaultValue(0));//章节列表链接类型。1表示current，是相对小说页面的相对地址；2表示home,即相对于渠道主页的地址
        $this->addColumn('{{%ditch}}', 'detailRule', $this->string(30));//小说详情采集规则

    }

    public function down()
    {
        echo "m160714_090235_alter_ditch cannot be reverted.\n";

        return false;
    }
}
