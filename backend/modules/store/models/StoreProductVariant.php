<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

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
 * @property StoreProductVariantLang[] $storeProductVariantLangs
 */
class StoreProductVariant extends \backend\components\BackModel
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
            [['label', 'sku'], 'string', 'max' => 255],
            [['id', 'product_id', 'label', 'sku', 'price', 'position'], 'safe', 'on' => 'search']
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
    public function getStoreProductVariantLangs()
    {
        return $this->hasMany(StoreProductVariantLang::className(), ['model_id' => 'id']);
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
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['like', 'sku', $this->sku]);
            $query->andFilterWhere(['price' => $this->price]);
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
                'label',
                'sku',
                'price',
                'position',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'product_id',
                'label',
                'sku',
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
                'type' => Form::INPUT_TEXT,
            ],
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'sku' => [
                'type' => Form::INPUT_TEXT,
            ],
            'price' => [
                'type' => Form::INPUT_TEXT,
            ],
            'position' => [
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
        return 'StoreProductVariant';
    }
}
