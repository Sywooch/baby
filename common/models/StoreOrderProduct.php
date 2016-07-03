<?php

namespace common\models;

use frontend\modules\store\models\StoreProductSize;
use Yii;

/**
 * This is the model class for table "{{%store_order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $size_id
 * @property integer $cert_id
 * @property integer $qnt
 * @property string $sku
 *
 * @property StoreOrder $order
 * @property StoreProduct $product
 * @property StoreProductSize $size
 */
class StoreOrderProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_order_product}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(StoreOrder::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(StoreProductSize::className(), ['id' => 'size_id']);
    }
}
