<?php

namespace backend\modules\store\models;

use backend\components\AutoIncrementBehavior;
use backend\modules\store\components\EAVBehavior;
use backend\modules\store\widgets\imagesUpload\ImageUpload;
use backend\widgets\cloneableInput\CloneableInput;
use common\models\Currency as CommCurrency;
use common\models\EntityToFile;
use common\models\Language;
//use common\models\StoreCategory;
use common\models\StoreProductSkuList;
use common\models\StoreProductType as CommonStoreProductType;
use kartik\select2\Select2;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use omgdef\multilingual\MultilingualBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%store_product}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $category_id
 * @property string $label
 * @property string $alias
 * @property string $announce
 * @property string $content
 * @property string $sku
 * @property string $price
 * @property string $video_id
 * @property integer $visible
 * @property integer $position
 * @property integer $show_on_main_page
 * @property integer $is_new
 * @property integer $is_top_50
 * @property integer $is_top_50_category
 * @property string $created
 * @property string $modified
 *
 * @property StoreCategory $category
 * @property StoreProductType $type
 * @property StoreProductLang[] $storeProductLangs
 */
class StoreProduct extends \backend\components\BackModel
{
    /**
     * Temporary sign which used for saving images before model save
     * @var
     */
    public $sign;

    /**
     * @var StoreProductVariant[]
     */
    public $variants;

    /**
     * @var StoreSimilarProduct[]
     */
    public $similar;

    /**
     * @var array
     */
    public $images = [];

    /**
     * @var array
     */
    public $filters = [];

    public function init()
    {
        parent::init();

        $this->prepareTempSign();
        $this->prepareProductSku();
    }

    public function prepareTempSign()
    {
        if (!$this->sign) {
            $this->sign = \Yii::$app->security->generateRandomString();
        }
    }

    /**
     * @param null $sku
     */
    public function prepareProductSku($sku = null)
    {
        $this->sku = StoreProductSkuList::getNewSku($sku);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->with(['translations']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'label', 'alias'], 'required'],
            ['alias', 'unique'],
            [['type_id', 'status', 'is_top_50', 'is_new', 'is_sale', 'is_popular', 'is_top_50_category', 'category_id', 'visible', 'position', 'show_on_main_page'], 'integer'],
            [['announce', 'content', 'video_id'], 'string'],
            [['price', 'old_price'], 'number'],
            [['created', 'modified', 'options', 'variants', 'sign', 'multiLangOptions', 'similar', 'filters'], 'safe'],
            ['options', 'validateOptions'],
            [['label', 'alias', 'sku'], 'string', 'max' => 255],
            [
                [
                    'id',
                    'type_id',
                    'category_id',
                    'label',
                    'alias',
                    'announce',
                    'content',
                    'sku',
                    'price',
                    'visible',
                    'position',
                    'created',
                    'modified'
                ],
                'safe',
                'on' => 'search'
            ]
        ];
    }

    /**
     * @return bool
     */
    public function validateOptions()
    {
        foreach ($this->options as $optionId => $optionValue) {
            $option = StoreProductAttribute::findOne($optionId);

            if (!$option) {
                $this->addError($this->options[$optionId], 'Неизвестная характеристика');
            }

            if ($option->is_required &&
                $option->type != StoreProductAttribute::TYPE_BOOLEAN &&
                (empty($optionValue) || $optionValue === '')
            ) {

                $this->addError(
                    $this->options[$optionId],
                    'Необходимо заполнить характеристику "' . $option->label . '"'
                );
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        /*$this->loadVariants();
        $this->loadFilters();
        $this->loadSimilar();*/
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        /*$this->saveVariants();
        $this->saveFilters();
        $this->saveSimilarProducts();*/
        $this->updateImages();
        StoreProductSkuList::saveNewSku($this->sku, $this->id);
    }

    public function afterValidate()
    {
        parent::afterValidate();

        if ($this->isNewRecord) {
            $this->prepareProductSku($this->sku);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        EntityToFile::deleteImages('StoreProduct', $this->id);
    }

    protected function loadVariants()
    {
        $this->variants = (new Query())
            ->from(StoreProductVariant::tableName())
            ->where(['product_id' => $this->id])
            ->orderBy('position')
            ->all();

        $this->loadMultiLangVariants();
    }

    protected function loadFilters()
    {
        $this->filters = (new Query())
            ->select('filter_id')
            ->from(StoreProductFilterToProduct::tableName())
            ->where('product_id = :pid', [':pid' => $this->id])
            ->column();
    }

    protected function loadSimilar()
    {
        $this->similar = ArrayHelper::map(
            (new Query())
            ->select(['similar_product_id', static::tableName().'.label'])
            ->innerJoin(static::tableName(), 'similar_product_id = '.static::tableName().'.id')
            ->from(StoreSimilarProduct::tableName())
            ->where('product_id = :pid', [':pid' => $this->id])
            ->all(),
            'similar_product_id',
            'label'
        );

        $this->similar = empty($this->similar) ? '' : Json::encode($this->similar);
    }

    protected function loadMultiLangVariants()
    {
        if ($this->getBehavior('ml')) {
            $multiLangVariantData = [];

            $multiLangVariants = (new Query())
                ->from(StoreProductVariantLang::tableName())
                ->where(['model_id' => ArrayHelper::getColumn($this->variants, 'id')])
                ->all();

            //Index array elements by model_id
            if (!empty($multiLangVariants)) {
                foreach ($multiLangVariants as $mVariant) {
                    $multiLangVariantData[$mVariant['model_id']][] = $mVariant;
                }


                //Fill variants array with multilang labels
                foreach ($this->variants as $i => $variant) {
                    if (isset($multiLangVariantData[$variant['id']])) {
                        foreach ($multiLangVariantData[$variant['id']] as $mVData) {
                            $this->variants[$i]['label_' . $mVData['lang_id']] = $mVData['label'];
                        }
                    }
                }
            }
        }
    }

    protected function saveVariants()
    {
        $this->deleteVariants();
        $data = [];

        $i = 1;
        foreach ($this->variants as $variant) {
            if ($variant['label'] != '') {

                $data[] = [
                    'product_id' => $this->id,
                    'label' => $variant['label'],
                    'sku' => $variant['sku'],
                    'price' => (float)$variant['price'],
                    'position' => $i
                ];

            }

            $i++;
        }

        if (!empty($data)) {
            \Yii::$app->db->createCommand()
                ->batchInsert(
                    StoreProductVariant::tableName(),
                    [
                        'product_id',
                        'label',
                        'sku',
                        'price',
                        'position'
                    ],
                    $data
                )
                ->execute();
        }

        $this->saveMultiLangVariants();
    }

    protected function saveFilters()
    {
        $this->deleteFilters();
        $data = [];
        if (!empty($this->filters)) {
            foreach ($this->filters as $filterId) {
                //Check if filter connected with the same category as product
                $isTheSameCat = (new Query())
                    ->from(StoreProductFilter::tableName())
                    ->where(['category_id' => $this->category_id])
                    ->andWhere(['id' => $filterId])
                    ->exists();

                if ($isTheSameCat) {
                    $data[] = [
                        'product_id' => $this->id,
                        'filter_id' => $filterId,
                    ];
                }
            }

            if (!empty($data)) {
                \Yii::$app->db->createCommand()
                    ->batchInsert(
                        StoreProductFilterToProduct::tableName(),
                        [
                            'product_id',
                            'filter_id',
                        ],
                        $data
                    )
                    ->execute();
            }
        }

    }

    protected function saveSimilarProducts()
    {
        $data = [];
        $this->deleteSimilar();

        $similar = explode(',', $this->similar);

        foreach ($similar as $simProdId) {
            if ($simProdId != '') {
                $data[] = [
                    'product_id' => $this->id,
                    'similar_product_id' => $simProdId,
                ];
            }

        }

        if (!empty($data)) {
            \Yii::$app->db->createCommand()
                ->batchInsert(
                    StoreSimilarProduct::tableName(),
                    [
                        'product_id',
                        'similar_product_id',
                    ],
                    $data
                )
                ->execute();
        }
    }

    protected function saveMultiLangVariants()
    {
        $data = [];

        if ($this->getBehavior('ml')) {
            foreach ($this->variants as $variant) {
                $savedVariant = (new Query())
                    ->select(['id', 'label'])
                    ->from(StoreProductVariant::tableName())
                    ->where('product_id = :pid', [':pid' => $this->id])
                    ->andWhere('label = :label', [':label' => $variant['label']])
                    ->one();

                if ($savedVariant) {
                    $data[] = [
                        'model_id' => $savedVariant['id'],
                        'lang_id' => Language::getDefaultLang()->code,
                        'label' => $savedVariant['label']
                    ];

                    foreach (Language::getLangList() as $langCode => $val) {
                        if (isset($variant['label_'.$langCode])) {
                            $data[] = [
                                'model_id' => $savedVariant['id'],
                                'lang_id' => $langCode,
                                'label' => $variant['label_'.$langCode]
                            ];
                        }
                    }
                }
            }
        }

        if (!empty($data)) {
            \Yii::$app->db->createCommand()
                ->batchInsert(
                    StoreProductVariantLang::tableName(),
                    [
                        'model_id',
                        'lang_id',
                        'label'
                    ],
                    $data
                )
                ->execute();
        }
    }

    protected function deleteVariants()
    {
        \Yii::$app->db->createCommand()
            ->delete(StoreProductVariant::tableName(), ['product_id' => $this->id])
            ->execute();
    }

    protected function deleteFilters()
    {
        \Yii::$app->db->createCommand()
            ->delete(StoreProductFilterToProduct::tableName(), ['product_id' => $this->id])
            ->execute();
    }

    protected function deleteSimilar()
    {
        \Yii::$app->db->createCommand()
            ->delete(StoreSimilarProduct::tableName(), ['product_id' => $this->id])
            ->execute();
    }

    /**
     * @return array
     */
    protected function getVariantsMultilangLabels()
    {
        $isMultiLang = $this->getBehavior('ml');
        $attributes = $isMultiLang
            ? ['Название['.Language::getDefaultLang()->code.']']
            : ['Название'];

        if ($isMultiLang) {
            foreach (Language::getLangList() as $code => $label) {
                if ($code != Language::getDefaultLang()->code) {
                    $attributes[] = 'Название['.$code.']';
                }
            }
        }

        return ArrayHelper::merge($attributes, ['Цена', 'Артикул']);
    }

    /**
     * @return array
     */
    protected function getVariantsMultilangAttrs()
    {
        $isMultiLang = $this->getBehavior('ml');
        $attributes = ['label'];

        if ($isMultiLang) {
            foreach (Language::getLangList() as $code => $label) {
                if ($code != Language::getDefaultLang()->code) {
                    $attributes[] = 'label_'.$code;
                }
            }
        }

        return ArrayHelper::merge($attributes, ['price', 'sku']);
    }

    protected function updateImages()
    {
        Yii::$app->db->createCommand()
            ->update(
                EntityToFile::tableName(),
                [
                    'entity_model_id' => $this->id,
                    'temp_sign' => ''
                ],
                'temp_sign = :ts',
                [':ts' => $this->sign]
            )
            ->execute();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'eav' => [
                    'class' => EAVBehavior::className(),
                    'tableName' => '{{%store_product_eav}}',
                    'multiLangConfig' => [
                        'langTable' => '{{%store_product_eav_lang}}',
                        'defaultLang' => Language::getDefaultLang()->code,
                        'langTableFk' => 'model_id',
                        'langTableLanguageColumn' => 'lang_id',
                    ],
                    'entityColumn' => 'product_id',
                    'attributeColumn' => 'attribute_id',
                    'valueColumn' => 'value',
                ],
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                            return date("Y-m-d H:i:s");
                    }
                ],
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => StoreProductLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
                'seo' => [
                    'class' => MetaTagBehavior::className(),
                    'defaultLanguage' => Language::getDefaultLang()->locale
                ],
                'aib' => [
                    'class' => AutoIncrementBehavior::className(),
                    'fields' => [
                        'new_position',
                        'top_position',
                        'top_category_position'
                    ]
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
            'label', 'content', 'announce'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes =  [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Тип'),
            'category_id' => Yii::t('app', 'Категория'),
            'label' => Yii::t('app', 'Название'),
            'alias' => Yii::t('app', 'Ссылка'),
            'announce' => Yii::t('app', 'Краткое описание'),
            'content' => Yii::t('app', 'Описание'),
            'sku' => Yii::t('app', 'Артикул'),
            'price' => Yii::t('app', 'Цена'),
            'old_price' => Yii::t('app', 'Старая цена'),
            'visible' => Yii::t('app', 'Отображать'),
            'position' => Yii::t('app', 'Позиция'),
            'created' => Yii::t('app', 'Создано'),
            'modified' => Yii::t('app', 'Обновлено'),
            'show_on_main_page' => Yii::t('app', 'Показывать на главной'),
            'is_new' => 'Новинка',
            'is_sale' => 'Распродажа',
            'is_popular' => 'Популярное',
            'is_top_50' => 'ТОП-50',
            'is_top_50_category' => 'ТОП-50 категория',
            'video_id' => 'ID видео (Vimeo или Youtube)',
            'status' => 'Наличие'
        ];

        return $this->prepareAttributeLabels($attributes);
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
    public function getType()
    {
        return $this->hasOne(StoreProductType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(StoreProductLang::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainImage()
    {
        return $this->hasOne(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->where('entity_model_name = :emn', [':emn' => 'StoreProduct'])
            ->joinWith('file')
            ->orderBy('entity_to_file.position DESC');

    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find()
            ->with(['mainImage']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['position'=> SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['type_id' => $this->type_id]);
        $query->andFilterWhere(['category_id' => $this->category_id]);
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['announce' => $this->announce]);
        $query->andFilterWhere(['content' => $this->content]);
        $query->andFilterWhere(['like', 'sku', $this->sku]);
        $query->andFilterWhere(['price' => $this->price]);
        $query->andFilterWhere(['visible' => $this->visible]);
        $query->andFilterWhere(['position' => $this->position]);
        $query->andFilterWhere(['created' => $this->created]);
        $query->andFilterWhere(['modified' => $this->modified]);

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
                [
                    'attribute' => 'type_id',
                    'value' => $this->type->label

                ],
                [
                    'attribute' => 'category_id',
                    'value' => $this->category->label

                ],
                'label',
                'alias',
                'announce',
                'content',
                'sku',
                'price',
                'visible:boolean',
                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                /*[
                    'attribute' => 'type_id',
                    'filter' => CommonStoreProductType::getProductTypes(),
                    'value' => function (self $data) {
                            return $data->type->label;
                        }

                ],*/
                [
                    'attribute' => 'category_id',
                    'filter' => \common\models\StoreCategory::getCategoriesList(),
                    'value' => function (self $data) {
                            return $data->category->label;
                        }

                ],
                'label',
                'alias',
                'sku',
                [
                    'attribute' => 'price',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'status',
                    'filter' => \common\models\StoreProduct::getStatusList(),
                    'value' => function (self $data) {
                        return \common\models\StoreProduct::getStatus($data->status);
                    }

                ],
                [
                    'label' => 'Изображение',
                    'format' => 'raw',
                    'value' => function (self $data) {
                            $mainImage = $data->mainImage;
                            return $mainImage
                                ? FPM::image($data->mainImage->file_id, 'product', 'smallPreview')
                                : null;
                        }
                ],
                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getAttributesForTypeUrl($params = [])
    {
        return static::createUrl('/store/store-product/get-attributes-for-type', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getFilterForCategoryUrl($params = [])
    {
        return static::createUrl('/store/store-product/get-filter-for-category', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCropUrl($params = [])
    {
        return static::createUrl('/store/store-product/crop', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getSaveCropedImageUrl($params = [])
    {
        return static::createUrl('/store/store-product/save-cropped-image', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getSimilarProductUrl($params = [])
    {
        return static::createUrl('/store/store-product/get-similar-product', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getUpdateUrl($params = [])
    {
        return static::createUrl('/store/store-product/update', $params);
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            'form-set' => [
                'Основные' => [
                    'label' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => [
                            'class' => 's_name'
                        ]
                    ],
                    'alias' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => [
                            'class' => 's_alias'
                        ]
                    ],
                    /*'type_id' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => ArrayHelper::merge(
                            ['' => 'выберите'],
                            ArrayHelper::map(
                                StoreProductType::find()->orderBy('position DESC')->asArray()->all(),
                                'id',
                                'label'
                            )
                        ),
                        'options' => [
                            'class' => 'dependent',
                            'data-url' => static::getAttributesForTypeUrl(),
                            'data-name' => 'typeId'
                        ]
                    ],*/
                    'category_id' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => static::getCategoriesList(),
                        'options' => [
                            'class' => 'dependent',
                            'data-url' => static::getFilterForCategoryUrl(),
                            'data-name' => 'category_id'
                        ]
                    ],
                    'announce' => [
                        'type' => Form::INPUT_TEXTAREA,
                        'options' => [
                            'rows' => 7
                        ]
                    ],
                    'content' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => \vova07\imperavi\Widget::classname(),
                        'options' => [
                                    'model' => $this,
                                    'attribute' => 'content',
                                    'settings' => [
                                        'lang' => 'ru',
                                        'minHeight' => 250,
                                        'pastePlainText' => true,
                                        'buttonSource' => true,
                                        'replaceDivs' => false,
                                        'paragraphize' => false,
                                        'imageManagerJson' => Url::to(['/store/store-product/images-get']),
                                        'imageUpload' => Url::to(['/store/store-product/image-upload']),
                                        'plugins' => [
//                                            'clips',
                                            'imagemanager',
                                            'fullscreen'
                                        ]
                                    ]
                                ]
                    ],
                    'sku' => [
                        'type' => Form::INPUT_STATIC,
                    ],
                    'price' => [
                        'type' => Form::INPUT_TEXT,
                        //'hint' => 'цена указана для текущей главной валюты "'.CommCurrency::getDefaultCurrencyCode().'"'
                    ],
                    'old_price' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    /*'video_id' => [
                        'type' => Form::INPUT_TEXT,
                    ],*/
                    'status' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => \common\models\StoreProduct::getStatusList()
                    ],
                    /*'show_on_main_page' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],*/
                    'is_new' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
                    'is_sale' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
                    'is_popular' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
                    'visible' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
/*                    'is_top_50' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
                    'is_top_50_category' => [
                        'type' => Form::INPUT_CHECKBOX,
                        'hint' => 'Актуально только для корневых(главных) категорий'
                    ],*/

                ],
                /*'Характеристики' => [
                    'options' => [
                        'type' => Form::INPUT_RAW,
                        'value' => function (self $model, $index, $widget) {
                                return $model->type_id
                                    ? $model->getOptionListAsHtml()
                                    : 'выберите тип продукта';
                            }
                    ]
                ],
                'Варианты' => [
                    'options' => [
                        'type' => Form::INPUT_RAW,
                        'value' => CloneableInput::widget(
                                [
                                    'model' => $this,
                                    'attribute' => 'variants',
                                    'fieldToAppend' => '.field-product-variants',
                                    'itemToCount' => '.attribute-to-clone',
                                    'itemName' => $this->getVariantsMultilangLabels(),
                                    'inputName' => $this->getVariantsMultilangAttrs(),
                                    'sortable' => true
                                ]
                            ),
                    ],
                ],
                'Фильтры' => [
                    'images[]' => [
                        'type' => Form::INPUT_RAW,
                        'value' => function (self $model, $index, $widget) {
                                return $model->getFilterHtml();
                            }
                    ],
                ],
                'Похожие' => [
                    'similar[]' => [
                        'type' => Form::INPUT_RAW,
                        'value' => function (self $model, $index, $widget) {
                                return $model->getSimilarHtml();
                            }
                    ],
                ],*/
                'Изображения' => [
                    'images[]' => [
                        'type' => Form::INPUT_RAW,
                        'value' => ImageUpload::widget(
                                [
                                    'model' => $this,
                                    'attribute' => 'images[]'
                                ]
                            )
                    ],
                    //It's required for correct image saving. Please do not delete this, if
                    //you do not know what you do
                    'sign' => [
                        'type' => Form::INPUT_RAW,
                        'value' => function ($data) {
                                return Html::activeHiddenInput($data, 'sign');
                            }
                    ]
                ]
            ],

        ];
    }

    /**
     * @return string
     */
    public function getBreadCrumbRoot()
    {
        return 'Товары';
    }

    public function getFilterHtml($categoryId = null)
    {
        $categoryId = $categoryId ? $categoryId : $this->category_id;
        $emptyMessage = 'для выбора фильтров выберите подкатегорию';
        $filterList = StoreProductFilter::getFilterList($categoryId);

        if (empty($filterList)) {
            return $emptyMessage;
        }

        return Select2::widget(
            [
                'model' => $this,
                'attribute' => 'filters',
                'data' => $filterList,
                'options' => ['multiple' => true, 'placeholder' => 'Выберите фильтра']
            ]
        );
    }

    /**
     * @return string
     */
    public function getSimilarHtml()
    {
        return Select2::widget(
            [
                'model' => $this,
                'attribute' => 'similar',
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true,
                    'ajax' => [
                        'url' => static::getSimilarProductUrl(),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    ],
                    'initSelection' => new JsExpression('
                        function (element, callback) {
                            var data = [];
                            var elemData = JSON.parse(element.val());

                            if (elemData != "") {
                                 $.each(elemData, function(key, value){
                                     data.push({
                                                id: key,
                                                text: value
                                            });
                                 });
                                $("#storeproduct-similar").val(data.map(function(item){return item.id;}).join());

                                callback(data);
                            }
                        }
                    ')
                ],
                'options' => [
                    'multiple' => true,
                    'placeholder' => 'Выберите похожие товары']
            ]
        );
    }

    /**
     * @param null $typeId
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOptionListAsHtml($typeId = null)
    {
        $output = '';
        if ($typeId) {
            $type = StoreProductType::findOne($typeId);

            if (!$type) {
                throw new NotFoundHttpException;
            }

            $attributeList = $type->attributeList;
        } else {
            $attributeList = $this->type->attributeList;
        }

        foreach ($attributeList as $attr) {
            $output .= Html::beginTag('div', ['class' => 'row']);
            $output .= Html::beginTag('div', ['class' => 'col-sm-12']);
            $output .= Html::beginTag('div', ['class' => $attr->is_required ? 'form-group required' :'form-group']);

            switch ($attr->type){
                case StoreProductAttribute::TYPE_INPUT:
                    if ($this->isEavMultiLang()) {
                        foreach (Language::getLangList() as $key => $label) {
                            if ($key != Language::getDefaultLang()->code) {
                                $output .= Html::label(
                                    $attr->label.'['.$key.']',
                                    Html::getInputId($this, 'multiLangOptions[' . $attr->id . ']['.$key.']'),
                                    ['class' => 'control-label']
                                );
                                $output .= Html::activeTextInput(
                                    $this,
                                    'multiLangOptions[' . $attr->id . ']['.$key.']',
                                    ['class' => 'form-control']
                                );
                            } else {
                                $output .= Html::label($attr->label.'['.$key.']', Html::getInputId($this, 'options['.$attr->id.']'), ['class' => 'control-label']);
                                $output .= Html::activeTextInput($this, 'options['.$attr->id.']', ['class' => 'form-control']);
                            }
                        }
                    } else {
                        $output .= Html::label($attr->label, Html::getInputId($this, 'options['.$attr->id.']'), ['class' => 'control-label']);
                        $output .= Html::activeTextInput($this, 'options['.$attr->id.']', ['class' => 'form-control']);
                    }
                    break;
                case StoreProductAttribute::TYPE_DROPDOWN:
                    $output .= Html::label($attr->label, Html::getInputId($this, 'options['.$attr->id.']'), ['class' => 'control-label']);

                    $options = empty($attr->options) ? [] : ArrayHelper::getColumn($attr->options, 'label');
                    $output .= Html::activeDropDownList(
                        $this,
                        'options['.$attr->id.']',
                        ArrayHelper::merge(['' => 'выберите'], array_combine($options, $options)),
                        ['class' => 'form-control']
                    );
                    break;
                case StoreProductAttribute::TYPE_MULTISELECT:
                    $output .= Html::label($attr->label, Html::getInputId($this, 'options['.$attr->id.']'), ['class' => 'control-label']);
                    $options = empty($attr->options) ? [] : ArrayHelper::getColumn($attr->options, 'label');
                    $output .= Select2::widget([
                            'model' => $this,
                            'attribute' => 'options['.$attr->id.']',
                            'options' => ['placeholder' => 'выберите значения'],
                            'pluginOptions' => [
                                'tags' => $options,
                                'createSearchChoice' => function () {
                                        return null;
                                }
                            ],
                        ]);
                    break;
                case StoreProductAttribute::TYPE_BOOLEAN:
                    $output .= Html::activeCheckbox($this, 'options['.$attr->id.']', ['label' => $attr->label]);
                    break;
            }

            $output .= Html::endTag('div');
            $output .= Html::endTag('div');
            $output .= Html::endTag('div');
        }

        return $output;
    }

    /**
     ** @return array
     **/
    public static function getCategoriesList($showEmpty = true)
    {
        $categoryTree = StoreCategory::find(true)->orderBy('lft')->all();
        $items = [];

        foreach ($categoryTree as $category) {
            if ($category->id == 1) {
                if ($showEmpty) {
                    $items[''] = 'выберите';
                }
            } else {
                $items[$category->id] = str_repeat('--', $category->level) . ' ' . $category->label;
            }
        }

        return $items;
    }
}
