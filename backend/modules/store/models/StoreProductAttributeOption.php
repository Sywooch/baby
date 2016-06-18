<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%store_product_attribute_option}}".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $label
 * @property integer $position
 *
 * @property StoreProductAttribute $attribute
 * @property StoreProductAttributeOptionLang[] $storeProductAttributeOptionLangs
 */
class StoreProductAttributeOption extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_attribute_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'label'], 'required'],
            [['attribute_id', 'position'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['id', 'attribute_id', 'label', 'position'], 'safe', 'on' => 'search']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute_id' => Yii::t('app', 'Атрибут'),
            'label' => Yii::t('app', 'Значение'),
            'position' => Yii::t('app', 'Позиция'),
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
    public function getStoreProductAttributeOptionLangs()
    {
        return $this->hasMany(StoreProductAttributeOptionLang::className(), ['model_id' => 'id']);
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
            $query->andFilterWhere(['attribute_id' => $this->attribute_id]);
            $query->andFilterWhere(['like', 'label', $this->label]);
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
                'attribute_id',
                'label',
                'position',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'attribute_id',
                'label',
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
            
            'attribute_id' => [
                'type' => Form::INPUT_TEXT,
            ],
            'label' => [
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
        return 'StoreProductAttributeOption';
    }
}
