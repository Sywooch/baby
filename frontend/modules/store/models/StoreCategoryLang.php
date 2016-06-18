<?php

namespace app\modules\store\models;

use Yii;

/**
 * This is the model class for table "store_category_lang".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 * @property string $description
 */
class StoreCategoryLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_category_lang}}';
    }
}
