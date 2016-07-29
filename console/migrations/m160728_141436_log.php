<?php

use yii\db\Migration;

class m160728_141436_log extends Migration
{
    public function up()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'type' => $this->integer(1),
            'app' => $this->string(20),
            'controller' => $this->string(20),
            'action' => $this->string(20),
            'model' => $this->string(20),
            'function' => $this->string(20),
            'work' => $this->string(100),
            'note' => $this->text(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
    }

    public function down()
    {
        $this->dropTable('log');
    }
}
