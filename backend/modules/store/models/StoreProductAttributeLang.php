<?php

namespace backend\modules\store\models;

use Yii;

/**
 * This is the model class for table "store_product_attribute_lang".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 *
 * @property Language $lang
 * @property StoreProductAttribute $model
 */
class StoreProductAttributeLang extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_attribute_lang}}';
    }
}
