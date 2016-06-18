<?php

namespace app\modules\store\models;

use Yii;

/**
 * This is the model class for table "{{%store_similar_product}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $similar_product_id
 *
 * @property StoreProduct $product
 * @property StoreProduct $similarProduct
 */
class StoreSimilarProduct extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_similar_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'similar_product_id'], 'required'],
            [['product_id', 'similar_product_id'], 'integer']
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
            'similar_product_id' => 'Similar Product ID',
        ];
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
    public function getSimilarProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'similar_product_id']);
    }
}
