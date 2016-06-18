<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%callback}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class Callback extends ActiveRecord
{
    /**
     *
     */
    const STATUS_NEW = 0;
    /**
     *
     */
    const STATUS_HANDLED = 1;

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_NEW => 'Новый',
            static::STATUS_HANDLED => 'Обработан',
        ];
    }

    /**
     * @param $statusId
     *
     * @return string|null
     */
    public static function getStatus($statusId)
    {
        $statusList = static::getStatusList();

        return isset($statusList[$statusId]) ? $statusList[$statusId] : null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%callback}}';
    }
}
