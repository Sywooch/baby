<?php

namespace backend\modules\store\models;

use backend\components\BackModel;
use kartik\select2\Select2;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "store_product_type_size".
 *
 * @property integer $id
 * @property integer $product_type_id
 * @property string $label
 * @property string $height
 */
class StoreProductTypeSize extends BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_product_type_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_type_id', 'label'], 'required'],
            [['label', 'height'], 'string', 'max' => 255],
            [['id', 'product_type_id', 'label'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes =  [
            'id' => 'ID',
            'product_type_guid' => 'Вид изделия',
            'label' => 'Заголовок',
        ];

        return $this->prepareAttributeLabels($attributes);
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = static::find()->from(['t' => static::tableName()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['guid' => $this->guid]);
            $query->andFilterWhere(['product_type_guid' => $this->product_type_guid]);
            $query->andFilterWhere(['like', 't.label', $this->label]);
    
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
                'guid',
                'product_type_guid' => [
                    'attribute' => 'product_type_guid',
                    'value' => $this->productType->label
                ],
                'label',
            ]
            : [
                'guid',
                'product_type_guid' => [
                    'format' => 'raw',
                    'label' => 'Вид изделия',
                    'value' => function ($data) {
                        return $data->productType->label;
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
            
            'product_type_guid' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => Select2::className(),
                'options' => [
                    'data' => StoreProductType::getProductTypes(),
                    'options' => [
                        'placeholder' => 'Выберите вид изделия',
                    ],
                ]
            ],
            'label' => [
                'type' => Form::INPUT_TEXT,
                'options' => [
                    'class' => 's_name'
                ]
            ],
            'alias' => [
                'type' => Form::INPUT_HIDDEN,
                'options' => [
                    'class' => 's_alias'
                ]
            ],

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductType()
    {
        return $this->hasOne(StoreProductType::className(), ['guid' => 'product_type_guid']);
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
        return 'Стандартыне размеры';
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
