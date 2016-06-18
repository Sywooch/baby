<?php

namespace app\modules\store\models;

use common\models\Language;
use frontend\components\FrontModel;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_attribute_option}}".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $label
 * @property integer $position
 *
 * @property StoreProductAttribute $attribute
 * @property StoreProductAttributeOptionLang[] $storeProductAttributeOptionLangs
 */
class StoreProductAttributeOption extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_attribute_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'label'], 'required'],
            [['attribute_id', 'position'], 'integer'],
            [['label'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attribute_id' => 'Атрибут',
            'label' => 'Значение',
            'position' => 'Позиция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeRel()
    {
        return $this->hasOne(StoreProductAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductAttributeOptionLangs()
    {
        return $this->hasMany(StoreProductAttributeOptionLang::className(), ['model_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => StoreProductAttributeOptionLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return [
            'label'
        ];
    }
}
