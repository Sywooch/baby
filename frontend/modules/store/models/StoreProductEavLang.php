<?php

namespace app\modules\store\models;

use Yii;

/**
 * This is the model class for table "{{%store_product_eav_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $value
 */
class StoreProductEavLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_eav_lang}}';
    }
}
