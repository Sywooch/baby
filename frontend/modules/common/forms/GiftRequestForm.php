<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\common\forms;

use common\models\GiftRequest;
use rmrevin\yii\postman\ViewLetter;
use yii\base\Model;

/**
 * Class GiftRequestForm
 * @package frontend\modules\common\forms
 */
class GiftRequestForm extends Model
{

    /**
     * @var integer $sex
     */
    public $sex;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $phone
     */
    public $phone;

    /**
     * @var string $email
     */
    public $email;

    /**
     * @var string $receiver
     */
    public $receiver;

    /**
     * @var string $aboutReceiver
     */
    public $aboutReceiver;

    /**
     * @var string $aboutGift
     */
    public $aboutGift;

    /**
     * @var string $giftReason
     */
    public $giftReason;

    /**
     * @var integer $giftBudget
     */
    public $giftBudget;


    public function init()
    {
        parent::init();

        $this->sex = $this->sex ? $this->sex : GiftRequest::SEX_MALE;
        $this->giftBudget = $this->giftBudget ? $this->giftBudget : GiftRequest::BUDGET_ANY;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
            [['sex', 'giftBudget'], 'integer'],
            [['giftReason', 'aboutGift', 'phone', 'name', 'aboutReceiver', 'receiver', 'email'], 'string'],
            ['email', 'email']
        ];
    }

    public function save()
    {
        $model = new GiftRequest();
        $model->name = $this->name;
        $model->phone = $this->phone;
        $model->sex = $this->sex;
        $model->email = $this->email;
        $model->gift_budget = $this->giftBudget;
        $model->gift_reason = $this->giftReason;
        $model->about_gift = $this->aboutGift;
        $model->about_receiver = $this->aboutReceiver;
        $model->receiver = $this->receiver;
        $model->status = GiftRequest::STATUS_NEW;
        $model->created = $model->modified = (new \DateTime())->format('Y-m-d H:i:s');
        $model->save(false);

        $adminEmails = \Yii::$app->config->get('admin_email');

        if ($adminEmails) {
            $mail = (new ViewLetter())
                ->setSubject('Новый запрос на подбор подарка')
                ->setBodyFromView('gift_request', compact('model'));

            $emails = explode(',', $adminEmails);
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }

            $mail->send();
        }

        \common\models\StoreOrder::fireNewRequestEvent();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sex' => \Yii::t('frontend', 'get_gift_sex'),
            'name' => \Yii::t('frontend', 'get_gift_name'),
            'phone' => \Yii::t('frontend', 'get_gift_phone'),
            'email' => \Yii::t('frontend', 'get_gift_email'),
            'receiver' => \Yii::t('frontend', 'get_gift_receiver'),
            'aboutReceiver' => \Yii::t('frontend', 'get_gift_about_receiver'),
            'aboutGift' => \Yii::t('frontend', 'get_gift_about_gift'),
            'giftReason' => \Yii::t('frontend', 'get_gift_gift_reason'),
            'giftBudget' => \Yii::t('frontend', 'get_gift_gift_budget'),
        ];
    }

    /**
     * @return array
     */
    public static function getFormRequestRoute()
    {
        return ['/common/gift-request/send'];
    }
}
