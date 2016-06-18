<?php

namespace app\modules\banner\models;

use Yii;

/**
 * This is the model class for table "{{%banner_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 * @property string $small_label
 * @property string $content
 * @property string $href
 */
class BannerLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'lang_id', 'label', 'content'], 'required'],
            [['model_id'], 'integer'],
            [['lang_id'], 'string', 'max' => 5],
            [['label', 'small_label'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 500],
            [['href'], 'string', 'max' => 300]
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
            'small_label' => 'Small Label',
            'content' => 'Content',
            'href' => 'Href',
        ];
    }
}
