<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%configuration}}".
 *
 * @property integer $id
 * @property string $config_key
 * @property string $value
 * @property string $description
 * @property integer $type
 * @property string $created
 * @property string $modified
 *
 */
class Configuration extends ActiveRecord
{
    /**
     * integer
     */
    const TYPE_INTEGER = 1;

    /**
     * string
     */
    const TYPE_STRING = 2;

    /**
     * text
     */
    const TYPE_TEXT = 3;

    /**
     * html
     */
    const TYPE_HTML = 4;

    /**
     * file
     */
    const TYPE_FILE = 5;

    /**
     * array
     */
    const TYPE_ARRAY = 6;

    /**
     * boolean
     */
    const TYPE_BOOLEAN = 7;

    /**
     * float
     */
    const TYPE_FLOAT = 8;

    /**
     * image
     */
    const TYPE_IMAGE = 9;

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            static::TYPE_INTEGER => 'Целое число',
            static::TYPE_STRING => 'Строка',
            static::TYPE_TEXT => 'Текст',
            static::TYPE_FILE => 'Файл',
            static::TYPE_IMAGE => 'Изображение',
        ];
    }

    /**
     * @return null
     */
    public function getTypeText()
    {
        $array = static::getTypes();
        return isset($array[$this->type]) ? $array[$this->type] : null;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%configuration}}';
    }
}
