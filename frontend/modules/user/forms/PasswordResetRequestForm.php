<?php
namespace app\modules\user\forms;

use common\models\User;
use rmrevin\yii\postman\ViewLetter;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => \Yii::t('resetPasswordForm', 'There is no user with such email.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                $mail = (new ViewLetter())
                    ->setSubject(\Yii::t('resetPasswordForm', 'email_reset_subject_label'))
                    ->setBodyFromView('@emailDir/passwordResetToken.php', compact('user'))
                    ->addAddress($this->email);

                return $mail->send();
            }
        }

        return false;
    }
}
