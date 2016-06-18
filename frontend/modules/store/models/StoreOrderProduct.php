<?php

namespace app\modules\store\models;

use app\modules\certificate\models\Certificate;
use frontend\components\FrontModel;
use Yii;

/**
 * This is the model class for table "{{%store_order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $cert_id
 * @property integer $qnt
 * @property string $sku
 *
 * @property StoreOrder $order
 * @property StoreProduct $product
 */
class StoreOrderProduct extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_order_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'sku'], 'required'],
            [['order_id', 'product_id'], 'integer'],
            [['sku'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'sku' => 'Артикул товара',
        ];
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
    public function getCert()
    {
        return $this->hasOne(Certificate::className(), ['id' => 'cert_id']);
    }
}
