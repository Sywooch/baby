<?php

namespace backend\modules\store\models;

use common\models\Language;
use kartik\select2\Select2;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_filter}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $position
 * @property string $label
 *
 * @property StoreCategory $category
 * @property StoreProductFilterLang[] $storeProductFilterLangs
 * @property StoreProductFilterToProduct[] $storeProductFilterToProducts
 */
class StoreProductFilter extends \backend\components\BackModel
{
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
            [['label'], 'string', 'max' => 255],
            [['id', 'position', 'category_id', 'label'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'category_id' => 'Категория ',
            'label' => 'Название',
        ];

        return $this->prepareAttributeLabels($attributes);

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
                ],
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
    public static function find()
    {
        return parent::find()->with(['translations']);
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
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['position'=> SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['category_id' => $this->category_id]);
            $query->andFilterWhere(['like', 'label', $this->label]);
    
        return $dataProvider;
    }

    /**
    * @param bool $viewAction
    *
    * @return array
    */
    public function getViewColumns($viewAction = false)
    {
        return $viewAction
            ? [
                'id',
                'category_id',
                'label',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'category_id',
                    'filter' => static::getCategoryList(),
                    'value' => function ($data) {
                            return $data->category->label;
                        }
                ],
                'label',

                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'category_id' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => Select2::className(),
                'options' => [
                    'data' => static::getCategoryList(),
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите категорию']
                ]
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 2;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'Фильтра для категорий';
    }

    /**
     * @return array
     */
    public static function getCategoryList()
    {
        return ArrayHelper::map(
            StoreCategory::find(false)
                ->orderBy('lft')
                ->where('level > 2')
                ->asArray()
                ->all(),
            'id',
            'label'
        );
    }

    /**
     * @param $categoryId
     *
     * @return array
     */
    public static function getFilterList($categoryId)
    {
        return ArrayHelper::map(
            static::find()
                ->where('category_id = :cid', [':cid' => $categoryId])
                ->orderBy('position DESC')
                ->asArray()
                ->all(),
            'id',
            'label'
        );
    }
}
