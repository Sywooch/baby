<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%store_similar_product}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $similar_product_id
 *
 * @property StoreProduct $product
 * @property StoreProduct $similarProduct
 */
class StoreSimilarProduct extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_similar_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'similar_product_id'], 'required'],
            [['product_id', 'similar_product_id'], 'integer'],
            [['id', 'product_id', 'similar_product_id'], 'safe', 'on' => 'search']
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
            'similar_product_id' => 'Similar Product ID',
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
    public function getSimilarProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'similar_product_id']);
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
            $query->andFilterWhere(['similar_product_id' => $this->similar_product_id]);
    
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
                'similar_product_id',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'product_id',
                'similar_product_id',

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
                'type' => Form::INPUT_TEXT,
            ],
            'similar_product_id' => [
                'type' => Form::INPUT_TEXT,
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
        return 'StoreSimilarProduct';
    }
}
