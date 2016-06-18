<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%store_product_type_to_attribute}}".
 *
 * @property integer $type_id
 * @property integer $attribute_id
 *
 * @property StoreProductAttribute $attribute
 * @property StoreProductType $type
 */
class StoreProductTypeToAttribute extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_type_to_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'attribute_id'], 'required'],
            [['type_id', 'attribute_id'], 'integer'],
            [['type_id', 'attribute_id'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'attribute_id' => 'Attribute ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeRel()
    {
        return $this->hasOne(StoreProductAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(StoreProductType::className(), ['id' => 'type_id']);
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

            $query->andFilterWhere(['type_id' => $this->type_id]);
            $query->andFilterWhere(['attribute_id' => $this->attribute_id]);
    
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
                'type_id',
                'attribute_id',
            ]
            : [
                'type_id',
                'attribute_id',

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
            'type_id' => [
                'type' => Form::INPUT_TEXT,
            ],
            'attribute_id' => [
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
        return 'StoreProductTypeToAttribute';
    }

    /**
     * @param $typeId
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAttributeList($typeId)
    {
        return static::find()->where('type_id = :tid', [':tid' => (int)$typeId])->all();
    }
}
