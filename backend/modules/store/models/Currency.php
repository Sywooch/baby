<?php

namespace backend\modules\store\models;

use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $code
 * @property string $rate_to_default
 * @property integer $is_default
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class Currency extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'code', 'rate_to_default'], 'required'],
            [['rate_to_default'], 'number'],
            [['is_default', 'visible', 'position'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label', 'code'], 'string', 'max' => 255],
            [['id', 'label', 'code', 'rate_to_default', 'is_default', 'visible', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Название',
            'code' => 'Код',
            'rate_to_default' => 'Курс к главной валюте',
            'is_default' => 'Главная валюта',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
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
                ]
            ]
        );
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
            'sort' => ['defaultOrder' => ['position' => SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['like', 'code', $this->code]);
            $query->andFilterWhere(['rate_to_default' => $this->rate_to_default]);
            $query->andFilterWhere(['is_default' => $this->is_default]);
            $query->andFilterWhere(['visible' => $this->visible]);
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
                'label',
                'code',
                'rate_to_default',
                'is_default',
                'visible:boolean',
                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'label',
                'rate_to_default',
                'code',
                [
                    'attribute' => 'is_default',
                    'filter' => ['Нет', 'Да'],
                    'value' => function ($data) {
                            return $data->is_default ? 'Да' : 'Нет';
                    },
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
            
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'code' => [
                'type' => Form::INPUT_TEXT,
            ],
            'rate_to_default' => [
                'type' => Form::INPUT_TEXT,
            ],
            'is_default' => [
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
        return 'Валюта';
    }
}
