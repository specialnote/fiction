<?php

use yii\db\Migration;

class m160612_072921_fiction extends Migration
{
    public function up()
    {
        $this->createTable('{{%fiction}}', [
            'id' => $this->primaryKey(),
            'fictionKey' => $this->string(100),
            'ditchKey' => $this->string(80),
            'name' => $this->string(50),
            'description' => $this->text(),
            'author' => $this->string(50),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%fiction}}');
        echo "m160612_072921_fiction cannot be reverted.\n";

        return false;
    }

}
