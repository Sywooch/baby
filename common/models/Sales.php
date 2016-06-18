<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%sales}}".
 *
 * @property integer $id
 * @property integer $image_id
 * @property string $label
 * @property string $href
 * @property string $content
 * @property integer $type
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class Sales extends ActiveRecord
{
    /**
     *
     */
    const TYPE_WHITE = 1;

    /**
     *
     */
    const TYPE_YELLOW = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales}}';
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            static::TYPE_WHITE => 'Белый c рамкой',
            static::TYPE_YELLOW => 'Желтый',
        ];
    }

    /**
     * @param $typeId
     *
     * @return null
     */
    public static function getType($typeId)
    {
        $types = static::getTypeList();

        return isset($types[$typeId]) ? $types[$typeId] : null;
    }
}
