<?php

namespace backend\modules\user\models;

use backend\components\BackModel;
use metalguardian\fileProcessor\behaviors\UploadBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string $city
 * @property string $address
 * @property string $secondary_address
 * @property string $discount_card
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $avatar_file_id
 * @property integer $is_in_club
 */
class User extends \backend\components\BackModel
{
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'role', 'name', 'surname'], 'required'],
            ['status', 'default', 'value' => \common\models\User::STATUS_ACTIVE],
            ['password_hash', 'default', 'value' => ''],
            [['role', 'status', 'created_at', 'updated_at', 'is_in_club'], 'integer'],
            [['username', 'password', 'name', 'surname', 'phone', 'city', 'address', 'secondary_address', 'discount_card', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой email уже есть в базе'],
            [['id', 'username', 'name', 'surname', 'phone', 'city', 'address', 'secondary_address', 'discount_card', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'role', 'status', 'created_at', 'updated_at', 'avatar_file_id', 'is_in_club'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин(для админ-панели)',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'phone' => 'Телефон',
            'city' => 'Город',
            'address' => 'Адрес',
            'secondary_address' => 'Дополнительный адрес',
            'discount_card' => 'Номер скидочной карты',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Роль',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Модифицировн',
            'avatar_file_id' => 'Аватар',
            'is_in_club' => 'В клубе',
            'password' => 'Пароль'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        parent::beforeValidate();

        if ($this->isNewRecord) {
            $this->generateAuthKey();
        }

        if ($this->password) {
            $this->setPassword($this->password);
        }

        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     *
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $insert
            ? \common\models\User::createRetailCrmUser($this->id)
            : \common\models\User::updateRetailCrmUser($this->id);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
                ],
                'image' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'avatar_file_id',
                    'image' => true
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
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'username', $this->username]);
            $query->andFilterWhere(['like', 'name', $this->name]);
            $query->andFilterWhere(['like', 'surname', $this->surname]);
            $query->andFilterWhere(['like', 'phone', $this->phone]);
            $query->andFilterWhere(['like', 'city', $this->city]);
            $query->andFilterWhere(['like', 'address', $this->address]);
            $query->andFilterWhere(['like', 'secondary_address', $this->secondary_address]);
            $query->andFilterWhere(['like', 'discount_card', $this->discount_card]);
            $query->andFilterWhere(['like', 'auth_key', $this->auth_key]);
            $query->andFilterWhere(['like', 'password_hash', $this->password_hash]);
            $query->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);
            $query->andFilterWhere(['like', 'email', $this->email]);
            $query->andFilterWhere(['role' => $this->role]);
            $query->andFilterWhere(['status' => $this->status]);
            $query->andFilterWhere(['created_at' => $this->created_at]);
            $query->andFilterWhere(['updated_at' => $this->updated_at]);
            $query->andFilterWhere(['avatar_file_id' => $this->avatar_file_id]);
            $query->andFilterWhere(['is_in_club' => $this->is_in_club]);
    
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
                'name',
                'surname',
                'phone',
                'email',
                'role',
                'status',
                'created_at',
                'updated_at',
                'avatar_file_id',
                'is_in_club',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'name',
                'surname',
                'phone',
                'email',
                [
                    'attribute' => 'Аватар',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->avatar_file_id
                            ? Html::img(
                                FPM::src(
                                    $data->avatar_file_id,
                                    'profile',
                                    'avatar'
                                )
                            )
                            : null;
                    }
                ],
                [
                    'attribute' => 'status',
                    'filter' => \common\models\User::getStatusList(),
                    'value' => function ($data) {
                        return \common\models\User::getStatusName($data->status);
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
            'username' => [
                'type' => Form::INPUT_TEXT,
            ],
            'name' => [
                'type' => Form::INPUT_TEXT,
            ],
            'surname' => [
                'type' => Form::INPUT_TEXT,
            ],
            'phone' => [
                'type' => Form::INPUT_TEXT,
            ],
            'city' => [
                'type' => Form::INPUT_TEXT,
            ],
            'address' => [
                'type' => Form::INPUT_TEXT,
            ],
            'secondary_address' => [
                'type' => Form::INPUT_TEXT,
            ],
            'discount_card' => [
                'type' => Form::INPUT_TEXT,
            ],
            'email' => [
                'type' => Form::INPUT_TEXT,
            ],
            'role' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\User::getRoleList()
            ],
            'imagePreview' => [
                'type' => Form::INPUT_RAW,
                'value' => function (self $data) {
                    return $data->isNewRecord
                        ? null
                        : $data->getImagePreview($data->avatar_file_id, 'profile', 'avatar', User::className(), 'avatar_file_id');
                },
            ],
            'avatar_file_id' => [
                'type' => Form::INPUT_FILE,
            ],
            'is_in_club' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'password' => [
                'type' => Form::INPUT_PASSWORD,
                'hint' => 'Пароль для нового пользователя или новый пароль для существующего пользователя'
            ]

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
        return 'Пользователи';
    }

    /**
     * @inheritdoc
     */
    public function getButtonsList(BaseDataProvider $dataProvider, BackModel $model)
    {
        $buttons = parent::getButtonsList($dataProvider, $model);
        $buttons .= Html::a('Выгрузить пользователей', ['export'], ['class' => 'create pull-left left-margin bottom-margin btn btn-info']);

        return $buttons;
    }


    /**
     * @return array
     */
    public function getAttrsForExport()
    {
        return [
            'id',
            'name',
            'surname',
            'phone',
            'email',
        ];
    }
}
