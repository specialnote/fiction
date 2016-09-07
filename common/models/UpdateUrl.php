<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%update_url}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $updateTime
 */
class UpdateUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%update_url}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updateTime'], 'safe'],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'updateTime' => 'Update Time',
        ];
    }
}
