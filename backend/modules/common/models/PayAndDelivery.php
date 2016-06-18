<?php

namespace backend\modules\common\models;

use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%pay_and_delivery}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $price
 * @property integer $for_kiev
 * @property integer $for_regions
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property PayAndDeliveryLang[] $payAndDeliveryLangs
 */
class PayAndDelivery extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_and_delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'for_kiev', 'for_regions', 'position', 'visible'], 'integer'],
            [['name', 'price'], 'required'],
            [['created', 'modified'], 'safe'],
            [['name', 'price'], 'string', 'max' => 255],
            [['id', 'type_id', 'name', 'price', 'for_kiev', 'for_regions', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    public static function find()
    {
        return parent::find()->with(['translations']);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'type_id' => 'Тип',
            'name' => 'Описание доставки и оплаты',
            'price' => 'Стоимость',
            'for_kiev' => 'Для Киева',
            'for_regions' => 'Для регионов',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
            'visible' => 'Отображать'
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
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
                ],
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => PayAndDeliveryLang::className(),
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
            'price', 'name'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayAndDeliveryLangs()
    {
        return $this->hasMany(PayAndDeliveryLang::className(), ['model_id' => 'id']);
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
            'sort' => [
                'defaultOrder' => ['position' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['type_id' => $this->type_id]);
            $query->andFilterWhere(['like', 'name', $this->name]);
            $query->andFilterWhere(['like', 'price', $this->price]);
            $query->andFilterWhere(['for_kiev' => $this->for_kiev]);
            $query->andFilterWhere(['for_regions' => $this->for_regions]);
            $query->andFilterWhere(['position' => $this->position]);
            $query->andFilterWhere(['created' => $this->created]);
            $query->andFilterWhere(['modified' => $this->modified]);
    
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
                'type_id',
                'name',
                'price',
                'for_kiev',
                'for_regions',
                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'type_id',
                    'filter' => \common\models\PayAndDelivery::getTypeList(),
                    'value' => function (self $data) {
                        return \common\models\PayAndDelivery::getType($data->type_id);
                    }
                ],
                'name',
                'price',
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
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\PayAndDelivery::getTypeList()
            ],
            'name' => [
                'type' => Form::INPUT_TEXT,
            ],
            'price' => [
                'type' => Form::INPUT_TEXT,
            ],
            'for_kiev' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'for_regions' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
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
        return 'Доставка и оплата';
    }
}
