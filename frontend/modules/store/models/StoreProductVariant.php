<?php

namespace app\modules\store\models;

use backend\modules\store\models\StoreProductVariantLang;
use common\models\Currency;
use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_variant}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $label
 * @property string $sku
 * @property string $price
 * @property integer $position
 *
 * @property StoreProduct $product
 * @property StoreProductVariantLang[] $storeProductVariantLangs
 */
class StoreProductVariant extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_variant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'label', 'sku'], 'required'],
            [['product_id', 'position'], 'integer'],
            [['price'], 'number'],
            [['label', 'sku'], 'string', 'max' => 255]
        ];
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
                    'tableName' => StoreProductVariantLang::tableName(),
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'label' => 'Название',
            'sku' => 'Артикул',
            'price' => 'Цена',
            'position' => 'Позиция',
        ];
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
    public function getStoreProductVariantLangs()
    {
        return $this->hasMany(StoreProductVariantLang::className(), ['model_id' => 'id']);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return Currency::getPriceInCurrency($this->price);
    }
}
