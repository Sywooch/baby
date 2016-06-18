<?php
namespace app\modules\user\forms;

use common\models\User;
use rmrevin\yii\postman\ViewLetter;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;

    public $name;

    public $surname;

    public $phone;

    public $discount_card;

    public $address;

    public $city;

    public $secondary_address;

    public $email;

    public $password;

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
            [
                [
                    'username',
                    'name',
                    'surname',
                    'phone',
                    'discount_card',
                    'discount_card',
                    'address',
                    'city',
                    'secondary_address'
                ],
                'filter',
                'filter' => 'trim'
            ],
            [
                [
                    'username',
                    'name',
                    'surname',
                    'phone',
                    'discount_card',
                    'discount_card',
                    'address',
                    'city',
                    'secondary_address'
                ],
                'string',
                'max' => '255'
            ],
            [['name', 'surname', 'email', 'password'], 'required'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('signupForm', 'This email address has already been taken.')
            ],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $needRetailUserCreate = false;

        if ($this->validate()) {
            $user = User::find()
                ->where([
                    'phone' => $this->phone,
                    'status' => User::STATUS_HAS_ORDERS_BUT_NOT_REGISTERED
                ])
                ->one();

            if (!$user) {
                $needRetailUserCreate = true;
                $user = new User();
            }

            $user->username = '';
            $user->name = $this->name;
            $user->surname = $this->surname;
            $user->phone = $this->phone;
            $user->city = $this->city;
            $user->discount_card = $this->discount_card;
            $user->address = $this->address;
            $user->secondary_address = $this->secondary_address;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;
            $user->save();

            $this->sendEmail($user);

            if ($needRetailUserCreate) {
                User::createRetailCrmUser($user->id);
            }

            return $user;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function sendEmail($user)
    {
        $adminEmails = \Yii::$app->config->get('admin_email');

        if ($adminEmails) {
            $mail = (new ViewLetter())
                ->setSubject('Новый пользователь на сайте')
                ->setBodyFromView('@emailDir/new_user.php', compact('user'));

            $emails = explode(',', $adminEmails);
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }

            $mail->send();
        }
    }
}
