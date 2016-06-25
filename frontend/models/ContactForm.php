<?php

namespace frontend\models;

use rmrevin\yii\postman\ViewLetter;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends ActiveRecord
{
/*    public $name;
    public $email;
    public $subject;
    public $body;*/
    //public $verifyCode;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['created', 'safe'],
            // verifyCode needs to be entered correctly
            //['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'name' => Yii::t('frontend', 'Name'),
            'body' => Yii::t('frontend', 'Feedback content'),
            'email' => Yii::t('frontend', 'Email'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail()
    {
        $adminEmail = \Yii::$app->config->get('admin_email');

        if ($adminEmail) {
            return (new ViewLetter())
                ->setSubject('Rays. Вы получили сообщение')
                ->setBodyFromView('@app/themes/basic/layouts/admin_email_template.php', ['model' => $this])
                ->addAddress($adminEmail)
                ->send();
        }

        return false;
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
                    'updatedAtAttribute' => 'created',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
                ],
            ]
        );
    }
}
