<?php

use yii\db\Migration;

class m160909_033525_alter_fiction extends Migration
{
    public function up()
    {
        $this->addColumn('fiction', 'imgUrl', $this->string(100));
    }

    public function down()
    {
        echo "m160909_033525_alter_fiction cannot be reverted.\n";

        return false;
    }
}
