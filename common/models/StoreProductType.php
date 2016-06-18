<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_type}}".
 *
 * @property integer $id
 * @property string $label
 * @property integer $position
 *
 * @property StoreProduct[] $storeProducts
 * @property StoreProductTypeLang[] $storeProductTypeLangs
 * @property StoreProductTypeToAttribute[] $storeProductTypeToAttributes
 * @property StoreProductAttribute[] $attributes
 */
class StoreProductType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_type}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Название',
            'position' => 'Позиция',
        ];
    }

    /**
     * @return array
     */
    public static function getProductTypes()
    {
        return ArrayHelper::map(StoreProductType::find()->all(), 'id', 'label');
    }
}
