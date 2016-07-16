<?php

use yii\db\Migration;

class m160714_085248_alter_category extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%category}}', 'key');
        $this->addColumn('{{%category}}', 'category_key', $this->string(50));
    }

    public function down()
    {
        echo "m160714_085248_alter_category cannot be reverted.\n";

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
