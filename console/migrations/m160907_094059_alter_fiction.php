<?php

use yii\db\Migration;

class m160907_094059_alter_fiction extends Migration
{
    public function up()
    {
        $this->addColumn('fiction', 'views', $this->integer());
    }

    public function down()
    {
        echo "m160907_094059_alter_fiction cannot be reverted.\n";

        return false;
    }
}
