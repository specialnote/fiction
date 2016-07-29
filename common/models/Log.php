<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $app
 * @property string $controller
 * @property string $action
 * @property string $model
 * @property string $function
 * @property string $work
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 */
class Log extends ActiveRecord
{
    const LOG_TYPE_FIND = 1;//没有找到数据
    const LOG_TYPE_SAVE = 2;//保存数据库失败
    const LOG_TYPE_CONFIG = 3;//配置信息错误
    const LOG_TYPE_GATHER = 4;//采集失败
    const LOG_TYPE_CACHE = 5;//缓存失败
    const LOG_TYPE_DB = 5;//数据库失败

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['app', 'controller', 'action', 'model', 'function'], 'string', 'max' => 20],
            [['work'], 'string', 'max' => 100],
            ['note', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'app' => 'App',
            'controller' => 'Controller',
            'action' => 'Action',
            'model' => 'Model',
            'function' => 'Function',
            'work' => 'Work',
            'note' => 'Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
}
