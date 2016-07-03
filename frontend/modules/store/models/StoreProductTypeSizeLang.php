<?php

namespace frontend\modules\store\models;

use frontend\components\FrontModel;
use Yii;

/**
 * This is the model class for table "store_product_type_size_lang".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 */
class StoreProductTypeSizeLang extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_product_type_size_lang';
    }
}
