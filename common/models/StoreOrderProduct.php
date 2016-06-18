<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%store_order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property string $sku
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
}
