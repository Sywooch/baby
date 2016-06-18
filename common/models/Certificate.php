<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%certificate}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $price
 * @property integer $color
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class Certificate extends ActiveRecord
{
    /**
     *
     */
    const COLOR_YELLOW = 1;
    /**
     *
     */
    const COLOR_PURPLE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificate}}';
    }

    /**
     * @return array
     */
    public static function getColors()
    {
        return [
            static::COLOR_YELLOW => 'Желтый',
            static::COLOR_PURPLE => 'Фиолетовый'
        ];
    }

    /**
     * @param $color
     *
     * @return null|string
     */
    public static function getColor($color)
    {
        $items = static::getColors();

        return isset($items[$color]) ? $items[$color] : null;
    }
}
