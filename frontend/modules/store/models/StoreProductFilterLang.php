<?php

namespace app\modules\store\models;

use Yii;

/**
 * This is the model class for table "{{%store_product_filter_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 */
class StoreProductFilterLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_filter_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'lang_id', 'label'], 'required'],
            [['model_id'], 'integer'],
            [['lang_id'], 'string', 'max' => 5],
            [['label'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_id' => 'L ID',
            'model_id' => 'Model ID',
            'lang_id' => 'Lang ID',
            'label' => 'Label',
        ];
    }
}
