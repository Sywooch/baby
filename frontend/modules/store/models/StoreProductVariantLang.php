<?php

namespace app\modules\store\models;

use Yii;

/**
 * This is the model class for table "{{%store_product_variant_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 *
 * @property Language $lang
 * @property StoreProductVariant $model
 */
class StoreProductVariantLang extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_variant_lang}}';
    }
}
