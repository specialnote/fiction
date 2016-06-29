<?php

use yii\db\Migration;

class m160612_072509_category_item extends Migration
{
    public function up()
    {
        $this->createTable('{{%category_item}}',[
           'id'=>$this->primaryKey(),
            'item_id'=>$this->integer(),
            'category_id'=>$this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%category_item}}');
        echo "m160612_072509_category_item cannot be reverted.\n";

        return false;
    }
}
