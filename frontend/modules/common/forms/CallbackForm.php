<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\common\forms;

use common\models\Callback;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class CallbackForm
 *
 * @package app\modules\common\forms
 */
class CallbackForm extends Model
{

    /**
     * @var string $phone
     */
    public $phone;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => \Yii::t('frontend', 'Callback_form_phone')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['phone', 'required'],
            ['phone', 'string'],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
        ];
    }

    public function save()
    {
        $model = new Callback();
        $model->name = '';//TODO после уточнения заполнить
        $model->phone = $this->phone;
        $model->created = $model->modified = (new \DateTime())->format('Y-m-d H:i:s');
        $model->save(false);

        $adminEmails = \Yii::$app->config->get('admin_email');

        if ($adminEmails) {
            $emailBody = Html::tag('p', 'Новый запрос на обратный звонок. Номер заявки #' . $model->id);
            $emailBody .= Html::tag('p', 'Телефон, указанный в запросе: ' . Html::tag('strong', $this->phone));

            $mail = (new \rmrevin\yii\postman\RawLetter())
                ->setSubject('Новый запрос на обратный звонок')
                ->setBody($emailBody);

            $emails = explode(',', $adminEmails);
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }

            $mail->send();
        }

        \common\models\StoreOrder::fireNewRequestEvent();
    }

    /**
     * @return string
     */
    public static function getFormUrl()
    {
        return Url::toRoute('/common/callback/callback');
    }
}
