<?php

namespace backend\modules\banner\models;

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
class BannerLang extends \backend\components\BackModel
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
