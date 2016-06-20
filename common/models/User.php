<?php
namespace common\models;

use common\components\RetailCrmHelper;
use metalguardian\fileProcessor\helpers\FPM;
use rmrevin\yii\postman\RawLetter;
use vova07\console\ConsoleRunner;
use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $surname
 * @property string $city
 * @property string $phone
 * @property string $address
 * @property string $secondary_address
 * @property string $discount_card
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_profile_filled
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_HAS_ORDERS_BUT_NOT_REGISTERED = 20;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public static function getRoleList()
    {
        return [
            static::ROLE_ADMIN => 'админ',
            static::ROLE_USER => 'пользователь',
        ];
    }

    /**
     * @param $id
     *
     * @return null
     */
    public static function getRoleName($id)
    {
        $roleList = static::getRoleList();

        return isset($roleList[$id]) ? $roleList[$id] : null;
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_ACTIVE => 'активный',
            static::STATUS_DELETED => 'удален',
            static::STATUS_HAS_ORDERS_BUT_NOT_REGISTERED => 'есть заказы без регистрации',
        ];
    }

    /**
     * @param $id
     *
     * @return null
     */
    public static function getStatusName($id)
    {
        $statusList = static::getStatusList();

        return isset($statusList[$id]) ? $statusList[$id] : null;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(static::getStatusList())],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($username)
    {
        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * @param array $options
     * @return null|string
     */
    public function getAvatarImage($options = [])
    {
        return $this->avatar_file_id
            ? FPM::image($this->avatar_file_id, 'profile', 'avatar', $options)
            : \yii\helpers\Html::img('/img/demo/user-hi.png', $options);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @return string
     */
    public function getAddressForRetailCrm()
    {
        return $this->address . ' ' . $this->city;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
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
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $userId
     */
    public static function createRetailCrmUser($userId)
    {
        $consoleRunner = new ConsoleRunner(['file' => '@app/../yii']);
        $consoleRunner->run('retail-crm/create-new-user ' . $userId);
    }

    /**
     * @param $userId
     */
    public static function updateRetailCrmUser($userId)
    {
        $consoleRunner = new ConsoleRunner(['file' => '@app/../yii']);
        $consoleRunner->run('retail-crm/update-user ' . $userId);
    }

    /**
     * @param Model $orderForm
     *
     * @return $this|int|string|void
     */
    public static function getUserIdForOrder(Model $orderForm)
    {
        $authenticatedUserId = Yii::$app->user->id;

        if ($authenticatedUserId) {
            return $authenticatedUserId;
        }

        $existUserId = (new Query())
            ->select('id')
            ->from(static::tableName())
            ->where(['phone' => $orderForm->phone]);

        if ($orderForm->email) {
            $existUserId = $existUserId->orWhere(['email' => $orderForm->email]);
        }

        $existUserId = $existUserId->scalar();

        if ($existUserId) {
            return $existUserId;
        }

        return static::createUserWithoutRegistration($orderForm);
    }

    /**
     * @param Model $orderForm
     *
     * @return int
     * @throws HttpException
     */
    public static function createUserWithoutRegistration(Model $orderForm)
    {
        $user = new User();
        $user->username = '';
        $user->name = $orderForm->name;
        $user->surname = '';
        $user->phone = $orderForm->phone;
        $user->city = '';
        $user->discount_card = $orderForm->discountCard;
        $user->address = $orderForm->address ? $orderForm->address : $orderForm->novaPoshtaStorage;
        $user->secondary_address = '';
        $user->email = $orderForm->email;
        $user->status = static::STATUS_HAS_ORDERS_BUT_NOT_REGISTERED;
        $user->setPassword('not_registered_user_pass');
        $user->generateAuthKey();
        if (!$user->save()) {
            (new RawLetter())
                ->setSubject('can not create user without registration')
                ->setBody('can not create user without registration '. json_encode($user->getErrors()))
                ->addAddress('pavel@vintage.com.ua')
                ->send();

            return null;
        }

        //RetailCrmHelper::createUser($user->id);

        return $user->id;
    }
}
