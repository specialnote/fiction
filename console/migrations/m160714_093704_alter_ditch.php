<?php

use yii\db\Migration;

class m160714_093704_alter_ditch extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%ditch}}', 'item_id');
    }

    public function down()
    {
        echo "m160714_093704_alter_ditch cannot be reverted.\n";

        return false;
    }
}
