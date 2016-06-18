<?php

namespace backend\modules\store\models;

use backend\widgets\cloneableInput\CloneableInput;
use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "store_product_attribute".
 *
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property integer $show_in_filter
 * @property integer $is_required
 * @property integer $position
 *
 * @property StoreProductAttributeLang[] $storeProductAttributeLangs
 */
class StoreProductAttribute extends \backend\components\BackModel
{

    /**
     * @var array
     */
    public $options = [];

    const TYPE_INPUT = 1;

    const TYPE_DROPDOWN = 2;

    const TYPE_MULTISELECT = 3;

    const TYPE_BOOLEAN = 4;

    public function getTypeList()
    {
        return [
            static::TYPE_INPUT => 'Поле ввода',
            static::TYPE_DROPDOWN => 'Выпадающий список',
            static::TYPE_MULTISELECT => 'Множественный выбор',
            static::TYPE_BOOLEAN => 'Да/Нет',
        ];
    }

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
    public static function find()
    {
        return parent::find()->with(['translations']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'alias'], 'required'],
            [['alias'], 'unique'],
            [['type', 'show_in_filter', 'is_required', 'position'], 'integer'],
            [['label', 'alias'], 'string', 'max' => 255],
            [['label'], 'unique'],
            ['options', 'safe'],
            [['id', 'label', 'type', 'show_in_filter', 'is_required', 'position'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'label' => 'Название',
            'type' => 'Тип',
            'show_in_filter' => 'Показывать в блоке фильтров',
            'is_required' => 'Обязателен ли к заполнению',
            'position' => 'Позиция',
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
                    'tableName' => StoreProductAttributeLang::className(),
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
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['type' => $this->type]);
        $query->andFilterWhere(['show_in_filter' => $this->show_in_filter]);
        $query->andFilterWhere(['is_required' => $this->is_required]);
        $query->andFilterWhere(['position' => $this->position]);

        return $dataProvider;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductAttributeLangs()
    {
        return $this->hasMany(StoreProductAttributeLang::className(), ['model_id' => 'id']);
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
                'label',
                'type',
                'show_in_filter:boolean',
                'is_required:boolean',
                'position',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'label',
                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->updateOptions();
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->getOptions();
    }

    protected function getOptions()
    {
        $this->options = (new Query())
            ->select(['id', 'label'])
            ->from(StoreProductAttributeOption::tableName())
            ->orderBy('position')
            ->where('attribute_id = :aid', [':aid' => $this->id])
            ->all();

        $this->loadMultiLangOptions();
    }

    protected function loadMultiLangOptions()
    {


        if ($this->getBehavior('ml')) {
            $multiLangOptionData = [];

            $multiLangOptions = (new Query())
                ->from(StoreProductAttributeOptionLang::tableName())
                ->where(['model_id' => ArrayHelper::getColumn($this->options, 'id')])
                ->all();

            //Index array elements by model_id
            if (!empty($multiLangOptions)) {
                foreach ($multiLangOptions as $mOption) {
                    $multiLangOptionData[$mOption['model_id']][] = $mOption;
                }


                //Fill option array with multilang labels
                foreach ($this->options as $i => $option) {
                    if (isset($multiLangOptionData[$option['id']])) {
                        foreach ($multiLangOptionData[$option['id']] as $mOData) {
                            $this->options[$i]['label_' . $mOData['lang_id']] = $mOData['label'];
                        }
                    }
                }
            }
        }
    }

    protected function updateOptions()
    {
        $options = [];

        StoreProductAttributeOption::deleteAll('attribute_id = :aid', [':aid' => $this->id]);

        if (in_array($this->type, [static::TYPE_DROPDOWN, static::TYPE_MULTISELECT])) {
            $i = 0;
            foreach ($this->options as $option) {
                if ($option && $option != '') {
                    $options[] = [
                        'attribute_id' => $this->id,
                        'label' => is_array($option) ? $option['label'] : $option,
                        'position' => $i
                    ];
                    $i++;
                }
            }

            if (!empty($options)) {
                Yii::$app->db->createCommand()->batchInsert(
                    StoreProductAttributeOption::tableName(),
                    ['attribute_id', 'label', 'position'],
                    $options
                )->execute();
            }
        }

        $this->updateMultiLangOptions();

    }

    protected function updateMultiLangOptions()
    {
        if ($this->getBehavior('ml')) {
            $data = [];

            if ($this->getBehavior('ml')) {
                foreach ($this->options as $option) {
                    $savedOption = (new Query())
                        ->select(['id', 'label'])
                        ->from(StoreProductAttributeOption::tableName())
                        ->where('attribute_id = :pid', [':pid' => $this->id])
                        ->andWhere('label = :label', [':label' => $option['label']])
                        ->one();

                    if ($savedOption) {
                        $data[] = [
                            'model_id' => $savedOption['id'],
                            'lang_id' => Language::getDefaultLang()->code,
                            'label' => $savedOption['label']
                        ];

                        foreach (Language::getLangList() as $langCode => $val) {
                            if (isset($option['label_'.$langCode])) {
                                $data[] = [
                                    'model_id' => $savedOption['id'],
                                    'lang_id' => $langCode,
                                    'label' => $option['label_'.$langCode]
                                ];
                            }
                        }
                    }
                }
            }

            if (!empty($data)) {
                \Yii::$app->db->createCommand()
                    ->batchInsert(
                        StoreProductAttributeOptionLang::tableName(),
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
                    'type' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => $this->getTypeList(),
                        'options' => [
                            'class' => 'product-attribute-type'
                        ]
                    ],

                    'show_in_filter' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
                    'is_required' => [
                        'type' => Form::INPUT_CHECKBOX,
                    ],
                ],
                'Опции' => [
                    'options' => [
                        'type' => Form::INPUT_RAW,
                        'value' => CloneableInput::widget(
                            [
                                    'model' => $this,
                                    'attribute' => 'options', //eav
                                    'fieldToAppend' => '.field-product-attribute',
                                    'itemToCount' => '.attribute-to-clone',
                                    'itemName' => $this->getOptionsMultilangLabels(),
                                    'inputName' => $this->getOptionsMultilangAttrs(),
                                    'sortable' => true
                            ]
                        ),
                    ],
                ]
            ]


        ];
    }

    /**
     * @return array
     */
    protected function getOptionsMultilangLabels()
    {
        $isMultiLang = $this->getBehavior('ml');
        $attributes = $isMultiLang
            ? ['Значение['.Language::getDefaultLang()->code.']']
            : ['Значения'];

        if ($isMultiLang) {
            foreach (Language::getLangList() as $code => $label) {
                if ($code != Language::getDefaultLang()->code) {
                    $attributes[] = 'Значения['.$code.']';
                }
            }
        }

        return $attributes;
    }

    /**
     * @return array
     */
    protected function getOptionsMultilangAttrs()
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

        return $attributes;
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
        return 'Атрибуты';
    }
}
