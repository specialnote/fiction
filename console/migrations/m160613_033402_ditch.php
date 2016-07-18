<?php

use yii\db\Migration;

class m160613_033402_ditch extends Migration
{
    public function up()
    {
        $this->createTable('{{%ditch}}', [
            'id' => $this->primaryKey(),
            'ditchKey' => $this->string(80),
            'type' => $this->integer(3),
            'url' => $this->string(100),
            'name' => $this->string(50),
            'status' => $this->integer(3),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%ditch}}');
        echo "m160613_033402_ditch cannot be reverted.\n";

        return false;
    }
}
