<?php

namespace backend\modules\store\models;

use kartik\select2\Select2;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_must_have}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $position
 *
 * @property StoreProduct $product
 */
class StoreProductMustHave extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_must_have}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'position'], 'integer'],
            [['id', 'product_id', 'position'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Продукт',
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
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = static::find()->joinWith(['product']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['position'=> SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'store_product.label', $this->product_id]);
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
                'product_id',
                'position',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'product_id',
                    'value' => function ($data) {
                            return $data->product->label;
                        }
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
        $products = ArrayHelper::map(
            StoreProduct::find()
            ->select(['store_product.id', 'label'])
            ->joinWith(['mainImage'])
            ->asArray()
            ->all(),
            'id',
            'label'
        );

        return [
            'product_id' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => Select2::className(),
                'options' => [
                    'data' => $products,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите продукт']
                ]
            ]
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
        return 'Must have';
    }
}
