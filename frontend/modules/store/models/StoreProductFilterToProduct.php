<?php

namespace app\modules\store\models;

use Yii;

/**
 * This is the model class for table "{{%store_product_filter_to_product}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $filter_id
 *
 * @property StoreProductFilter $filter
 * @property StoreProduct $product
 */
class StoreProductFilterToProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_filter_to_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'filter_id'], 'required'],
            [['product_id', 'filter_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'filter_id' => 'Filter ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilter()
    {
        return $this->hasOne(StoreProductFilter::className(), ['id' => 'filter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }
}
