<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_product_subscribe}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $email
 * @property string $phone
 * @property integer $status
 * @property string $created
 * @property string $modified
 *
 * @property StoreProduct $product
 */
class StoreProductSubscribe extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_subscribe}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'email', 'phone'], 'required'],
            [['product_id', 'user_id', 'status'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['user_name', 'email', 'phone'], 'string', 'max' => 255],
            [['id', 'product_id', 'user_id', 'user_name', 'email', 'phone', 'status', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Продукт',
            'user_id' => 'Пользователь',
            'user_name' => 'Имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'status' => 'Статус',
            'created' => 'Создано',
            'modified' => 'Обновлено',
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
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', StoreProduct::tableName().'.label', $this->product_id]);
            $query->andFilterWhere(['user_id' => $this->user_id]);
            $query->andFilterWhere(['like', 'user_name', $this->user_name]);
            $query->andFilterWhere(['like', 'email', $this->email]);
            $query->andFilterWhere(['like', 'phone', $this->phone]);
            $query->andFilterWhere([static::tableName().'.status' => $this->status]);
            $query->andFilterWhere([static::tableName().'.created' => $this->created]);
            $query->andFilterWhere([static::tableName().'.modified' => $this->modified]);
    
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
                'user_name',
                'email',
                'phone',
                'status',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'product_id',
                    'value' => function (self $data) {
                        return $data->product->label;
                    }
                ],
                'user_name',
                'email',
                'phone',
                [
                    'attribute' => 'status',
                    'filter' => \common\models\StoreProductSubscribe::getStatusList(),
                    'value' => function (self $data) {
                        return \common\models\StoreProductSubscribe::getStatus($data->status);
                    }
                ],
                'created',
                'modified',
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
                'type' => $this->isNewRecord ? Form::INPUT_TEXT : Form::INPUT_STATIC,
                'append' => $this->isNewRecord
                    ? ''
                    : 'Название:'.$this->product->label
            ],
//            'user_id' => [
//                'type' => Form::INPUT_TEXT,
//            ],
            'user_name' => [
                'type' => Form::INPUT_TEXT,
            ],
            'email' => [
                'type' => Form::INPUT_TEXT,
            ],
            'phone' => [
                'type' => Form::INPUT_TEXT,
            ],
            'status' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\StoreProductSubscribe::getStatusList()
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
        return 'Подписка на наличие товара';
    }
}
