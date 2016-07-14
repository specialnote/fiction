<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%fiction}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $author
 * @property string $fiction_key
 */
class Fiction extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fiction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name', 'author'], 'string', 'max' => 50],
            [['fiction_key'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'author' => 'Author',
            'fiction_key' => 'Fiction Key',
        ];
    }
}
