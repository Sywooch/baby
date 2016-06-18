<?php

namespace common\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%store_product_eav}}".
 *
 * @property integer $product_id
 * @property integer $attribute_id
 * @property string $value
 *
 * @property StoreProductAttribute $attribute
 * @property StoreProduct $product
 */
class StoreProductEav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_eav}}';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['product_id', 'attribute_id'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeRel()
    {
        return $this->hasOne(StoreProductAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return string
     */
    public function getAttributeValue()
    {
        switch ($this->attributeRel->type) {
            case StoreProductAttribute::TYPE_BOOLEAN:
                return $this->value ? 'Да' : 'Нет';
                break;
            default:
                return $this->value;
        }
    }
}
