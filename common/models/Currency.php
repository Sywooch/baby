<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $code
 * @property string $rate_to_default
 * @property integer $is_default
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class Currency extends ActiveRecord
{
    /**
     * @return null|array
     */
    public static $default = null;

    /**
     * @return null|array
     */
    public static $current = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @return null|array
     */
    public static function getDefaultCurrency()
    {
        if (!static::$default) {
            static::$default  = Currency::find()->where(['is_default' => 1])->asArray()->one();
        }

        return static::$default;
    }

    /**
     * @param string $code
     *
     * @return null|array
     */
    //TODO переделать current, когда введем мультивалюту
    public static function getCurrentCurrency($code)
    {
        if (!static::$current) {
            static::$current  = Currency::find()->where('code = :code', [':code' => $code])->asArray()->one();
        }

        return static::$current;
    }

    /**
     * @return string
     */
    public static function getDefaultCurrencyCode()
    {
        $default = static::getDefaultCurrency();

        return $default ? $default['code'] : 'N/A';
    }

    /**
     * @param $price
     * @param string $currency
     *
     * @return float
     */
    public static function getPriceInCurrency($price, $currency = 'UAH')
    {
        $currency = static::getCurrentCurrency($currency);

        return $currency ? static::roundUp($currency['rate_to_default'] * $price) : $price;
    }

    /**
     * Round any number to closest tenth
     * I.e. roundUp(159) = 160; roundUp(151) = 160
     *
     * @param $number
     *
     * @return float
     */
    protected static function roundUp($number)
    {
        return ceil($number / 10) * 10;
    }
}
