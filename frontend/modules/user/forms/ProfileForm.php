<?php
namespace app\modules\user\forms;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class ProfileForm extends Model
{
    public $name;
    public $surname;
    public $phone;
    public $discount_card;
    public $address;
    public $city;
    public $secondary_address;
//    public $email;
//    public $password;

    public function init()
    {
        parent::init();

        $user = Yii::$app->user->identity;

        $this->name = $user->name;
        $this->surname = $user->surname;
        $this->phone = str_replace('+380 ', '', $user->phone);
        $this->city = $user->city;
        $this->discount_card = $user->discount_card;
        $this->address = $user->address;
        $this->secondary_address = $user->secondary_address;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('signupForm', 'Name'),
            'surname' => Yii::t('signupForm', 'Surname'),
            'phone' => Yii::t('signupForm', 'Phone'),
            'discount_card' => Yii::t('signupForm', 'Discount card'),
            'address' => Yii::t('signupForm', 'Address'),
            'city' => Yii::t('signupForm', 'City'),
            'secondary_address' => Yii::t('signupForm', 'Secondary address'),
            'email' => Yii::t('signupForm', 'Email'),
            'password' => Yii::t('signupForm', 'Password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'phone', 'discount_card', 'discount_card', 'address', 'city', 'secondary_address'], 'filter', 'filter' => 'trim'],
            [['name', 'surname', 'phone', 'discount_card', 'discount_card', 'address', 'city', 'secondary_address'], 'string', 'max' => '255'],
            [['name', 'surname'], 'required'],
//            ['email', 'filter', 'filter' => 'trim'],
//            ['email', 'email'],
//            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('signupForm', 'This email address has already been taken.')],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
        ];
    }

    /**
     * @return null|\yii\web\IdentityInterface
     */
    public function save()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;
            $user->name = $this->name;
            $user->surname = $this->surname;
            $user->phone = $this->phone;
            $user->city = $this->city;
            $user->discount_card = $this->discount_card;
            $user->address = $this->address;
            $user->secondary_address = $this->secondary_address;
            $user->save();

            \common\models\User::updateRetailCrmUser($user->id);

            return $user;
        }

        return null;
    }
}
