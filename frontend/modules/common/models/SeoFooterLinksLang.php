<?php

namespace app\modules\common\models;

use Yii;

/**
 * This is the model class for table "{{%seo_footer_links_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 * @property string $link
 */
class SeoFooterLinksLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_footer_links_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'lang_id', 'label', 'link'], 'required'],
            [['model_id'], 'integer'],
            [['lang_id'], 'string', 'max' => 5],
            [['label', 'link'], 'string', 'max' => 255]
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
            'link' => 'Link',
        ];
    }
}
