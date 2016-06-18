<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\store\forms;

use common\models\StoreProductSubscribe;
use rmrevin\yii\postman\ViewLetter;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class StoreProductSubscribeForm
 * @package frontend\modules\store\forms
 */
class StoreProductSubscribeForm extends Model
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $email;

    /**
     * @var integer
     */
    public $productId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'string'],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
            ['phone', 'checkPhoneAndEmail', 'skipOnEmpty' => false],
            ['email', 'email'],
            ['productId', 'integer', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('frontend', 'Name'),
            'phone' => \Yii::t('frontend', 'Phone'),
        ];
    }

    /**
     * @return bool
     */
    public function checkPhoneAndEmail()
    {
        if (empty($this->phone) && empty($this->email)) {
            $this->addError('email', \Yii::t('frontend', 'Phone or email must be set.'));
            return false;
        }
        $existRequest = StoreProductSubscribe::find();
        if (empty($this->phone) && !empty($this->email)) {
            $existRequest = $existRequest ->where(
                'product_id = :product_id AND email = :email',
                [
                    ':email' => $this->email,
                    ':product_id' => $this->productId
                ]
            )->exists();
        } elseif (!empty($this->phone) && empty($this->email)) {
            $existRequest = $existRequest ->where(
                'product_id = :product_id AND phone = :phone',
                [
                    ':phone' => $this->phone,
                    ':product_id' => $this->productId
                ]
            )->exists();
        } elseif (!empty($this->phone) && !empty($this->email)) {
            $existRequest = $existRequest ->where(
                'product_id = :product_id AND (phone = :phone OR email = :email)',
                [
                    ':phone' => $this->phone,
                    ':email' => $this->email,
                    ':product_id' => $this->productId
                ]
            )->exists();
        }

        if ($existRequest) {
            $this->addError('email', \Yii::t('frontend', 'You are already subscribed for this product'));
            return false;
        }

        return true;
    }

    public function save()
    {
        $model = new StoreProductSubscribe();
        $model->product_id = $this->productId;
        $model->phone = $this->phone;
        $model->email = $this->email;
        $model->user_name = $this->name;
        $model->created = $model->modified = (new \DateTime())->format('Y-m-d H:i:s');
        $model->save(false);

        $adminEmails = \Yii::$app->config->get('admin_email');

        if ($adminEmails) {
            $mail = (new ViewLetter())
                ->setSubject('Новый запрос на наличие товара')
                ->setBodyFromView('subscribe', [
                    'id' => $model->id,
                    'alias' => $model->product->alias,
                    'name' => $model->user_name,
                    'email' => $model->email,
                    'phone' => $model->phone
                ]);

            $emails = explode(',', $adminEmails);
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }

            $mail->send();
        }
    }

    /**
     * @return string
     */
    public static function getFormUrl($params = [])
    {
        return Url::toRoute(ArrayHelper::merge(['/store/product-subscribe/subscribe'], $params));
    }
}
