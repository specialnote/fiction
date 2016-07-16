<?php

use yii\db\Migration;

class m160714_085107_alter_ditch extends Migration
{
    public function up()
    {
        $this->addColumn('{{%ditch}}', 'ditch_key', $this->string(80));
    }

    public function down()
    {
        echo "m160714_085107_alter_ditch cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
