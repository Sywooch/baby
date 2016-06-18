<?php

namespace backend\modules\common\models;

use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%gift_request}}".
 *
 * @property integer $id
 * @property integer $sex
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $receiver
 * @property string $about_receiver
 * @property string $about_gift
 * @property string $gift_reason
 * @property integer $gift_budget
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class GiftRequest extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_request}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'gift_budget', 'status'], 'integer'],
            [['phone'], 'required'],
            [['about_receiver', 'about_gift'], 'string'],
            [['created', 'modified'], 'safe'],
            [['name', 'phone', 'email', 'receiver', 'gift_reason'], 'string', 'max' => 255],
            [
                [
                    'id',
                    'sex',
                    'name',
                    'phone',
                    'email',
                    'receiver',
                    'about_receiver',
                    'about_gift',
                    'gift_reason',
                    'gift_budget',
                    'status',
                    'created',
                    'modified'
                ],
                'safe',
                'on' => 'search'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sex' => 'Пол',
            'name' => 'Имя отправителя',
            'phone' => 'Телефон отправителя',
            'email' => 'Email отправителя',
            'receiver' => 'Кому подарок',
            'about_receiver' => 'Про получателя',
            'about_gift' => 'Какой должен быть ваш подарок',
            'gift_reason' => 'Повод для подарка',
            'gift_budget' => 'Бюджет',
            'status' => 'Статус',
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
                ],
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
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC]
                ]
            ]
        );

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['sex' => $this->sex]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'phone', $this->phone]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'receiver', $this->receiver]);
        $query->andFilterWhere(['about_receiver' => $this->about_receiver]);
        $query->andFilterWhere(['about_gift' => $this->about_gift]);
        $query->andFilterWhere(['like', 'gift_reason', $this->gift_reason]);
        $query->andFilterWhere(['gift_budget' => $this->gift_budget]);
        $query->andFilterWhere(['status' => $this->status]);
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
                'sex',
                'name',
                'phone',
                'email',
                'receiver',
                'about_receiver',
                'about_gift',
                'gift_reason',
                'gift_budget',
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
                    'attribute' => 'sex',
                    'filter' => \common\models\GiftRequest::getSexList(),
                    'value' => function (self $data) {
                        return \common\models\GiftRequest::getSex($data->sex);
                    }
                ],
                [
                    'attribute' => 'gift_budget',
                    'filter' => \common\models\GiftRequest::getBudgetList(),
                    'value' => function (self $data) {
                        return \common\models\GiftRequest::getBudget($data->gift_budget);
                    }
                ],
                'name',
                'phone',
                'email',
                [
                    'attribute' => 'status',
                    'filter' => \common\models\GiftRequest::getStatusList(),
                    'value' => function (self $data) {
                        return \common\models\GiftRequest::getStatus($data->status);
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
        return [

            'sex' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\GiftRequest::getSexList()
            ],
            'name' => [
                'type' => Form::INPUT_TEXT,
            ],
            'phone' => [
                'type' => Form::INPUT_TEXT,
            ],
            'email' => [
                'type' => Form::INPUT_TEXT,
            ],
            'receiver' => [
                'type' => Form::INPUT_TEXT,
            ],
            'about_receiver' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],
            'about_gift' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],
            'gift_reason' => [
                'type' => Form::INPUT_TEXT,
            ],
            'gift_budget' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\GiftRequest::getBudgetList()
            ],
            'status' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\GiftRequest::getStatusList()
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
        return 'Запросы на подбор подарка';
    }
}
