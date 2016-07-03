<?php

namespace backend\modules\store\models;

use backend\components\CustomMultipleInput;
use common\models\Language;
use kartik\select2\Select2;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_type}}".
 *
 * @property integer $id
 * @property string $label
 * @property integer $position
 *
 * @property StoreProductTypeLang[] $storeProductTypeLangs
 * @property StoreProductAttribute[] $attributeList
 */
class StoreProductType extends \backend\components\BackModel
{
    public $typeSizes = [];
    public $attrs = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_type}}';
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
            [['label'], 'required'],
            [['position'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['attrs', 'typeSizes'], 'safe'],
            [['id', 'label', 'position'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Название'),
            'position' => Yii::t('app', 'Позиция'),
            'attrs' => Yii::t('app', 'Атрибуты')
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
                    'tableName' => StoreProductTypeLang::className(),
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

    public function afterFind()
    {
        parent::afterFind();

        $this->loadTypeSizes();
        /*$this->attrs = (new Query())
            ->select('attribute_id')
            ->from(StoreProductTypeToAttribute::tableName())
            ->where('type_id = :tid', [':tid' => $this->id])
            ->column();*/
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->saveTypeSizes();

        /*StoreProductTypeToAttribute::deleteAll('type_id = :tid', [':tid' => $this->id]);

        if (is_array($this->attrs)) {
            foreach ($this->attrs as $attr) {
                $model = new StoreProductTypeToAttribute();
                $model->type_id = $this->id;
                $model->attribute_id = (int)$attr;
                $model->save(false);
            }
        }*/
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
            $query->andFilterWhere(['position' => $this->position]);
    
        return $dataProvider;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeList()
    {
        return $this->hasMany(StoreProductAttribute::className(), ['id' => 'attribute_id'])
            ->viaTable(StoreProductTypeToAttribute::tableName(), ['type_id' => 'id'])->orderBy('position');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductTypeLangs()
    {
        return $this->hasMany(StoreProductTypeLang::className(), ['model_id' => 'id']);
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
    * @return array
    */
    public function getFormRows()
    {
        return [
            'form-set' => [
                'Основные' => [
                    'label' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    /*'attrs' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => Select2::className(),
                        'options' => [
                            'data' => ArrayHelper::map(StoreProductAttribute::find()->orderBy('position DESC')->asArray()->all(), 'id', 'label'),
                            'options' => ['multiple' => true, 'placeholder' => 'Выберите атрибуты типа']
                        ]
                    ]*/
                ],
                'Типовые размеры' => [
                    'typeSizes' => [
                        'type' => Form::INPUT_RAW,
                        'value' => CustomMultipleInput::widget(['model' => $this])
                    ],
                ],
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 1;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'Тип продукта';
    }

    private function loadTypeSizes()
    {
        $this->typeSizes = (new Query())
            ->select(['id', 'label', 'height'])
            ->from(StoreProductTypeSize::tableName())
            ->andWhere('product_type_id = :id', [':id' => $this->id])
            ->all();
    }

    private function saveTypeSizes()
    {
        $sizeIDs = ArrayHelper::getColumn($this->typeSizes, 'id');
        StoreProductTypeSize::deleteAll(['and', 'product_type_id = :id', ['not in', 'id', $sizeIDs]], [
            ':id' => $this->id
        ]);
        if (is_array($this->typeSizes)) {
            $data = [];
            foreach ($this->typeSizes as $size) {
                if ($size['id'] && $model = StoreProductTypeSize::findOne($size['id'])) {
                    $model->label = $size['label'];
                    $model->height = $size['height'];
                    $model->save();
                } else {
                    $data[] = [
                        'product_type_id' => $this->id,
                        'label' => $size['label'],
                        'height' => $size['height']
                    ];
                }
            }
            if (!empty($data)) {
                Yii::$app->db->createCommand()->batchInsert(
                    StoreProductTypeSize::tableName(),
                    ['product_type_id', 'label', 'height'],
                    $data
                )->execute();
            }
        }
    }
}
