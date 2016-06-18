<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%store_product_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 * @property string $announce
 * @property string $content
 *
 * @property Language $lang
 * @property StoreProduct $model
 */
class StoreProductLang extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_lang}}';
    }

}
