<?php

use yii\db\Migration;

class m160907_092347_update_url extends Migration
{
    public function up()
    {
        $this->createTable('{{%update_url}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(100),
            'updateTime' => $this->date()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%update_url}}');
    }
}
