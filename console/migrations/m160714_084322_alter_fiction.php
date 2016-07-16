<?php

use yii\db\Migration;

class m160714_084322_alter_fiction extends Migration
{
    public function up()
    {
        $this->addColumn('{{%fiction}}', 'fiction_key', $this->string(100));
    }

    public function down()
    {
        echo "m160714_084322_alter_fiction cannot be reverted.\n";

        return false;
    }
}
