<?php

namespace app\modules\store\models;

use common\models\Language;
use frontend\components\FrontModel;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_attribute}}".
 *
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property integer $show_in_filter
 * @property integer $is_required
 * @property integer $position
 *
 * @property StoreProductAttributeLang[] $storeProductAttributeLangs
 * @property StoreProductAttributeOption[] $storeProductAttributeOptions
 * @property StoreProductEav[] $storeProductEavs
 * @property StoreProductTypeToAttribute[] $storeProductTypeToAttributes
 * @property StoreProductType[] $types
 */
class StoreProductAttribute extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['type', 'show_in_filter', 'is_required', 'position'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['label'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Название',
            'type' => 'Тип',
            'show_in_filter' => 'Показывать в блоке фильтров',
            'is_required' => 'Обязателен ли к заполнению',
            'position' => 'Позиция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductAttributeLangs()
    {
        return $this->hasMany(StoreProductAttributeLang::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductAttributeOptions()
    {
        return $this->hasMany(StoreProductAttributeOption::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductEavs()
    {
        return $this->hasMany(StoreProductEav::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductTypeToAttributes()
    {
        return $this->hasMany(StoreProductTypeToAttribute::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(StoreProductType::className(), ['id' => 'type_id'])->viaTable('{{%store_product_type_to_attribute}}', ['attribute_id' => 'id']);
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
                    'tableName' => StoreProductAttributeLang::className(),
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
