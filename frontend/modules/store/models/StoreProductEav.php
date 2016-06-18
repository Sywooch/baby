<?php

namespace app\modules\store\models;

use common\models\Language;
use frontend\components\FrontModel;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_eav}}".
 *
 * @property integer $product_id
 * @property integer $attribute_id
 * @property string $value
 * @property integer $id
 *
 * @property StoreProductAttribute $attribute
 * @property StoreProduct $product
 * @property StoreProductEavLang[] $storeProductEavLangs
 */
class StoreProductEav extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_eav}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'attribute_id', 'value'], 'required'],
            [['product_id', 'attribute_id'], 'integer'],
            [['value'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'attribute_id' => 'Attribute ID',
            'value' => 'Value',
            'id' => 'ID',
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
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductEavLangs()
    {
        return $this->hasMany(StoreProductEavLang::className(), ['model_id' => 'id']);
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
                    'tableName' => StoreProductEavLang::className(),
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
            'value'
        ];
    }
}
