<?php

namespace app\modules\store\models;

use common\models\Language;
use frontend\components\FrontModel;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_filter}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $label
 * @property integer $position
 *
 * @property StoreCategory $category
 * @property StoreProductFilterLang[] $storeProductFilterLangs
 * @property StoreProductFilterToProduct[] $storeProductFilterToProducts
 */
class StoreProductFilter extends FrontModel
{

    /**
     * @var null|array
     */
    public static $catalogCategoryFilters = null;

    /**
     * @var null|array
     */
    public static $catalogCategoryFiltersLabels = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_filter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'label'], 'required'],
            [['category_id', 'position'], 'integer'],
            [['label'], 'string', 'max' => 255]
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
                    'tableName' => StoreProductFilterLang::className(),
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
            'category_id' => 'Category ID',
            'label' => 'Название',
            'position' => 'Позиция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(StoreCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductFilterLangs()
    {
        return $this->hasMany(StoreProductFilterLang::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductFilterToProducts()
    {
        return $this->hasMany(StoreProductFilterToProduct::className(), ['filter_id' => 'id']);
    }

    /**
     * @param $categoryId
     *
     * @return array|null
     */
    public static function getFiltersForCategory($categoryId)
    {
        if (!static::$catalogCategoryFilters) {
            static::$catalogCategoryFilters = StoreProductFilter::find()
                ->where(['category_id' => $categoryId])
                ->innerJoinWith(['storeProductFilterToProducts'])
                ->orderBy('position DESC')
                ->all();
        }

        return static::$catalogCategoryFilters;
    }

    /**
     * @return array|null
     */
    public static function getFilterLabels()
    {
        if (!static::$catalogCategoryFiltersLabels) {
            static::$catalogCategoryFiltersLabels = join(
                ', ',
                ArrayHelper::map(
                    ArrayHelper::toArray(
                        static::$catalogCategoryFilters,
                        [StoreProductFilter::className() => ['id', 'label']]
                    ),
                    'id',
                    'label'
                )
            );

            if (!empty(static::$catalogCategoryFiltersLabels)) {
                static::$catalogCategoryFiltersLabels = ', '. static::$catalogCategoryFiltersLabels;
            }
        }

        return static::$catalogCategoryFiltersLabels;
    }
}
