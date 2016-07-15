<?php

use yii\db\Migration;

class m160612_071511_category extends Migration
{
    public function up()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'ditch_key' => $this->string(32),
            'url' => $this->string(100),
            'category_key' => $this->string(32),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%category}}');
        echo "m160612_071511_category cannot be reverted.\n";

        return false;
    }
}
