<?php

namespace frontend\modules\store\models;

use app\modules\store\models\StoreProduct;
use common\models\Currency;
use frontend\components\FrontModel;
use Yii;

/**
 * This is the model class for table "store_product_size".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $product_type_size_id
 * @property integer $existence
 * @property string $price
 * @property string $old_price
 * @property integer $position
 * 
 * @property StoreProductTypeSize $typeSize
 * @property StoreProduct $product
 */
class StoreProductSize extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_product_size';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeSize()
    {
        return $this->hasOne(StoreProductTypeSize::className(), ['id' => 'product_type_size_id'])
            ->from(['sizeLabel' => StoreProductTypeSize::tableName()]);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return float
     */
    public function getPriceInCurrency()
    {
        return Currency::getPriceInCurrency($this->price);
    }

    /**
     * @return string
     */
    public function getPriceWithCurrency()
    {
        return $this->getPriceInCurrency() . ' ' . Currency::getDefaultCurrencyCode();
    }

    /**
     * @return float
     */
    public function getOldPriceInCurrency()
    {
        return Currency::getPriceInCurrency($this->old_price) ;
    }
}
