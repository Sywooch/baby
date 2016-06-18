<?php
namespace app\modules\user\forms;

use yii\base\Model;
use Yii;

/**
 * FullFillProfile form
 */
class FullFillProfileForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $discount_card;
    public $address;
    public $city;
    public $secondary_address;

    public function init()
    {
        parent::init();

        /**
         * @var $user \common\models\User
         */
        $user = Yii::$app->user->identity;

        $this->name = $user->name;
        $this->surname = $user->surname;
        $this->phone = str_replace('+380 ', '', $user->phone);
        $this->city = $user->city;
        $this->email = $user->email;
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
            [['name', 'surname', 'phone', 'email', 'discount_card', 'discount_card', 'address', 'city', 'secondary_address'], 'filter', 'filter' => 'trim'],
            [['name', 'surname', 'phone', 'email', 'discount_card', 'discount_card', 'address', 'city', 'secondary_address'], 'filter', 'filter'=>'\yii\helpers\HtmlPurifier::process'],
            [['name', 'surname', 'phone', 'email', 'discount_card', 'discount_card', 'address', 'city', 'secondary_address'], 'string', 'max' => '255'],
            [['name', 'surname', 'email', 'phone'], 'required'],
            ['email', 'email'],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
        ];
    }

    /**
     * @return null|\yii\web\IdentityInterface
     */
    public function save()
    {
        if ($this->validate()) {

            /**
             * @var $user \common\models\User
             */
            $user = Yii::$app->user->identity;
            $user->name = $this->name;
            $user->surname = $this->surname;
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->city = $this->city;
            $user->discount_card = $this->discount_card;
            $user->address = $this->address;
            $user->secondary_address = $this->secondary_address;
            $user->is_profile_filled = 1;
            $user->save();

            \common\models\User::createRetailCrmUser($user->id);

            return $user;
        }

        return null;
    }
}
