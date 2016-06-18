<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%store_product_subscribe}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $email
 * @property string $phone
 * @property integer $status
 * @property string $created
 * @property string $modified
 *
 * @property StoreProduct $product
 */
class StoreProductSubscribe extends ActiveRecord
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
            static::STATUS_HANDLED => 'Обработан'
        ];
    }

    /**
     * @param $statusId
     *
     * @return null|string
     */
    public static function getStatus($statusId)
    {
        $statusList = static::getStatusList();

        return isset($statusList[$statusId])
            ? $statusList[$statusId]
            : null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_subscribe}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }
}
