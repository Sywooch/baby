<?php

namespace backend\modules\blog\models;

use backend\modules\user\models\User;
use common\models\BlogArticle;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $article_id
 * @property string $content
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class Comment extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'article_id', 'content', 'created', 'modified'], 'required'],
            [['user_id', 'article_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['created', 'modified'], 'safe'],
            [['id', 'user_id', 'article_id', 'content', 'status', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'article_id' => 'Статья блога',
            'content' => 'Коммент',
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
        $query = static::find()->joinWith(['article', 'user']);
        $tableName = static::tableName();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

        $nameSurname = explode(' ', $this->user_id);

        $query->andFilterWhere([$tableName . '.id' => $this->id]);
        $query->andFilterWhere(['or', ['like', 'user.name', $nameSurname[0]], ['like', 'user.surname', $nameSurname[0]]]);
        if (isset($nameSurname[1])) {
            $query->andFilterWhere(['like', 'user.surname', $nameSurname[1]]);

        }
        $query->andFilterWhere(['like', \backend\modules\blog\models\BlogArticle::tableName() . '.label', $this->article_id]);
        $query->andFilterWhere(['like', $tableName . '.content', $this->content]);
        $query->andFilterWhere([$tableName . '.status' => $this->status]);
        $query->andFilterWhere([$tableName . '.created' => $this->created]);
        $query->andFilterWhere([$tableName . '.modified' => $this->modified]);
    
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
                'user_id',
                'article_id',
                'content',
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
                    'attribute' => 'user_id',
                    'value' => function ($data) {
                        return $data->user->getFullName();
                    }
                ],
                [
                    'attribute' => 'article_id',
                    'value' => function ($data) {
                        return $data->article->label;
                    }
                ],
                'content',
                [
                    'attribute' => 'status',
                    'filter' => \common\models\Comment::getStatusList(),
                    'value' => function ($data) {
                        return \common\models\Comment::getStatus($data->status);
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
            
            'user_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => ArrayHelper::map(\common\models\User::find()->all(), 'id', function ($data){ return $data->getFullName(); })
            ],
            'article_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => ArrayHelper::map(\backend\modules\blog\models\BlogArticle::find()->all(), 'id', 'label')
            ],
            'content' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],
            'status' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\Comment::getStatusList()
            ],
            'created' => [
                'type' => Form::INPUT_STATIC,
            ],
            'modified' => [
                'type' => Form::INPUT_STATIC,
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(\backend\modules\blog\models\BlogArticle::className(), ['id' => 'article_id']);
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'Комментарии';
    }
}
