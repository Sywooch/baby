<?php

namespace backend\modules\common\models;

use backend\components\BackModel;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%news_subscribe}}".
 *
 * @property integer $id
 * @property string $email
 * @property string $created
 * @property string $modified
 */
class NewsSubscribe extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_subscribe}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            [
                ['email'],
                'unique',
                'targetClass' => static::className(),
                'targetAttribute' => 'email',
                'message' => 'Такой Email уже есть в базе'
            ],
            [['created', 'modified'], 'safe'],
            [['email'], 'string', 'max' => 255],
            [['id', 'email', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
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
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'email', $this->email]);
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
                'email',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'email',
                'created',
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
            
            'email' => [
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
        return 'Подписки на рассылку';
    }

    /**
     * @inheritdoc
     */
    public function getButtonsList(BaseDataProvider $dataProvider, BackModel $model)
    {
        $buttons = parent::getButtonsList($dataProvider, $model);
        $buttons .= Html::a('Выгрузить данные', ['export'], ['class' => 'create pull-left left-margin bottom-margin btn btn-info']);

        return $buttons;
    }

    /**
     * @return array
     */
    public function getAttrsForExport()
    {
        return [
            'id',
            'email',
            'created',
            'modified'
        ];
    }
}
