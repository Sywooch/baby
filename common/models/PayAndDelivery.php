<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%pay_and_delivery}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $price
 * @property integer $for_kiev
 * @property integer $for_regions
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property PayAndDeliveryLang[] $payAndDeliveryLangs
 */
class PayAndDelivery extends ActiveRecord
{
    /**
     *
     */
    const TYPE_DELIVERY = 1;

    /**
     *
     */
    const TYPE_PAY = 2;

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            static::TYPE_DELIVERY => Yii::t('payAndDelivery', 'Delivery'),
            static::TYPE_PAY => Yii::t('payAndDelivery', 'Pay'),
        ];
    }

    /**
     * @param $typeId
     *
     * @return null|string
     */
    public static function getType($typeId)
    {
        $typeList = static::getTypeList();

        return isset($typeList[$typeId]) ? $typeList[$typeId] : null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_and_delivery}}';
    }
}
