<?php

namespace backend\modules\store\models;

use common\components\CommonConstants;
use common\models\StoreProductEav;
use kartik\select2\Select2;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "store_product_size".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $product_type_size_id
 * @property integer $existence
 * @property string $price
 * @property string $old_price
 * @property integer $position
 */
class StoreProductSize extends \backend\components\BackModel
{
    public $value;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_product_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_type_size_id', 'price', 'id'], 'required'],
            [['existence', 'product_id', 'product_type_size_id', 'position'], 'integer'],
            [['price', 'old_price'], 'number'],
            [['id', 'product_id', 'product_type_size_id', 'existence', 'price', 'position'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'ID изделия',
            'product_type_size_id' => 'Размер изделия',
            'existence' => 'Наличие',
            'price' => 'Цена размера',
            'old_price' => 'Старая цена',
            'position' => 'Позиция',
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
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['product_id' => $this->product_id]);
            $query->andFilterWhere(['product_type_size_id' => $this->product_type_size_id]);
            $query->andFilterWhere(['existence' => $this->existence]);
            $query->andFilterWhere(['weight' => $this->weight]);
            $query->andFilterWhere(['price' => $this->price]);
            $query->andFilterWhere(['show_price_per_gram' => $this->show_price_per_gram]);
            $query->andFilterWhere(['price_per_gram' => $this->price_per_gram]);
            $query->andFilterWhere(['position' => $this->position]);
    
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
                'id',
                'product_id',
                'product_type_size_id',
                'existence',
                'weight',
                'price',
                'show_price_per_gram',
                'price_per_gram',
                'position',
            ]
            : [
                'id',
                'product_id',
                'product_type_size_id' => [
                    'format' => 'raw',
                    'label' => 'Размер',
                    'value' => function ($data) {
                        return !empty($data->size) ? $data->size->label : '';
                    }
                ],
                                                                                                [
                    'attribute' => 'position',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],

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
            
            'product_id' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => Select2::className(),
                'options' => [
                    'data' => StoreProduct::getAllProducts(),
                    'options' => [
                        'placeholder' => 'Выберите изделие',
                        'class' => 'dependent',
                        'data-url' => '/store/store-product/change-sizes',
                        'data-name' => 'productGuid',
                    ],
                ],
            ],
            'product_type_size_id' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => Select2::className(),
                'options' => [
                    'data' => $this->getProductTypeSizes(),
                    'options' => [
                        'placeholder' => 'Выберите размер',
                        'class' => 'dependant-sizes',
                    ],
                ]
            ],
            'existence' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'weight' => [
                'type' => Form::INPUT_TEXT,
            ],
            'price' => [
                'type' => Form::INPUT_TEXT,
            ],
            'show_price_per_gram' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'price_per_gram' => [
                'type' => Form::INPUT_TEXT,
            ],
            'position' => [
                'type' => Form::INPUT_TEXT,
            ],

        ];
    }

    public function getProductTypeSizes()
    {
        if (isset($this->product->type_id)){
            return (new \yii\db\Query())
                ->select(['label'])
                ->from('store_product_type_size')
                ->where(['product_type_id' => $this->product->type_id])
                ->indexBy('id')
                ->column();
        }
        return [];
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
    public function getSize()
    {
        return $this->hasOne(StoreProductTypeSize::className(), ['id' => 'product_type_size_id'])
            /*->where(['product_type_id' => $this->product->type_id])*/;
    }

    /**
     * @return string
     */
    public function getSizeAlias()
    {
        return (new Query())
            ->select('alias')
            ->from(StoreProductTypeSize::tableName())
            ->where(['id' => $this->product_type_size_id])
            ->scalar();
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
        return 'Размер товара';
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        //$this->value = $this->size->alias;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        /*if ($this->existence){
            $value = $this->getSizeAlias();
            $data = [
                'product_id' => $this->product_id,
                'filter_id' => CommonConstants::GUID_SIZE,
                'value' => $value,
            ];
            if ($this->isNewRecord){
                (new Query())->createCommand()->insert(StoreProductEav::tableName(), $data)->execute();
            } else {
                $isUpdate = (new Query())->createCommand()->update(
                    StoreProductEav::tableName(),
                    ['value' => $value],
                    ['product_id' => $this->product_id, 'filter_id' => CommonConstants::GUID_SIZE, 'value' => $this->value]
                )->execute();
                if (!$isUpdate){
                    (new Query())->createCommand()->insert(StoreProductEav::tableName(), $data)->execute();
                }
            }
        } else {
            $this->deleteInEav();
        }*/
    }

    public function deleteInEav()
    {
        (new Query())->createCommand()->delete(
            StoreProductEav::tableName(),
            ['product_id' => $this->product_id, 'filter_id' => CommonConstants::GUID_SIZE, 'value' => $this->value]
        )->execute();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $this->deleteInEav();
    }
}
