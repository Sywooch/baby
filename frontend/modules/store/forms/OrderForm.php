<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\forms;

use app\modules\certificate\models\Certificate;
use app\modules\store\models\StoreOrderProduct;
use app\modules\store\models\StoreProductCartPosition;
use backend\components\BackModel;
use common\models\StoreOrder;
use common\models\User;
use frontend\components\PokuponChecker;
use rmrevin\yii\postman\ViewLetter;
use yii\base\Exception;
use yii\base\Model;
use yz\shoppingcart\CartPositionInterface;

/**
 * Class OrderForm
 * @package app\modules\store\forms
 */
class OrderForm extends Model
{
    /**
     * @var string $name
     */
    public $name;

    public $city;

    /**
     * @var string $name
     */
    public $phone;

    /**
     * @var string $name
     */
    public $email;

    /**
     * @var integer $paymentType
     */
    public $paymentType;

    /**
     * @var integer $deliveryType
     */
    public $deliveryType;

    /**
     * @var string $street
     */
    public $street;

    /**
     * @var string $address
     */
    public $address;

    /**
     * @var string $house
     */
    public $house;

    /**
     * @var string $apartment
     */
    public $apartment;

    /**
     * @var integer $deliveryTime
     */
    public $deliveryTime;

    /**
     * @var string $novaPoshtaStorage
     */
    public $novaPoshtaStorage;

    /**
     * @var string $comment
     */
    public $comment;

    /**
     * @var string $discountCard
     */
    public $discountCard;

    /**
     * @var string $promoCode
     */
    public $promoCode;

    /**
     * @var
     */
    protected $createdOrder;


    public function init()
    {
        if (!$this->paymentType) {
            $this->paymentType = 1;
        }

        if (!$this->deliveryType) {
            $this->deliveryType = 1;
        }

        $this->fillUserData();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('frontend', 'Name'),
            'city' => \Yii::t('frontend', 'Order_form_city'),
            'email' => \Yii::t('frontend', 'Order_form_email'),
            'phone' => \Yii::t('frontend', 'Phone'),
            'paymentType' => \Yii::t('frontend', 'Order_form_paymentType'),
            'deliveryType' => \Yii::t('frontend', 'Order_form_deliveryType'),
            'street' => \Yii::t('frontend', 'Order_form_street'),
            'address' => \Yii::t('frontend', 'Order_form_addres'),
            'house' => \Yii::t('frontend', 'Order_form_house'),
            'apartment' => \Yii::t('frontend', 'Order_form_apartment'),
            'deliveryTime' => \Yii::t('frontend', 'Order_form_deliveryTime'),
            'novaPoshtaStorage' => \Yii::t('frontend', 'Order_form_novaPoshtaStorage'),
            'comment' => \Yii::t('frontend', 'Order_form_comment'),
            'discountCard' => \Yii::t('frontend', 'Order_form_discountCard'),
            'promoCode' => \Yii::t('frontend', 'Order_form_promoCode'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['paymentType', 'deliveryType', 'deliveryTime'], 'integer'],
            [['name', 'phone', 'street', 'house', 'apartment', 'novaPoshtaStorage', 'city', 'comment', 'address', 'discountCard', 'promoCode'], 'string'],
            ['email', 'email'],
            [['phone'], 'common\components\validator\PhoneValidator', 'country' => 'UA'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if (\Yii::$app->cart->getIsEmpty()) {
            return false;
        }

        $orderItems = [];

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $array = [];
            if ($this->city) {
                $array[] = 'г. ' . $this->city;
            }
            if ($this->street) {
                $array[] = $this->street;
            }
            if ($this->house) {
                $array[] = 'д. ' . $this->house;
            }
            if ($this->apartment) {
                $array[] = 'кв. ' . $this->apartment;
            }
            $address = implode(' ', $array);
            $order = new \app\modules\store\models\StoreOrder();
            $order->user_id = User::getUserIdForOrder($this);
            $order->name = $this->name;
            $order->phone = $this->phone;
            $order->email = $this->email;
            $order->payment_type = $this->paymentType;
            $order->delivery_type = $this->deliveryType;
            $order->address = $address;
            $order->city = $this->city;
            $order->street = $this->street;
            $order->house = $this->house;
            $order->apartment = $this->apartment;
            $order->delivery_time = $this->deliveryTime;
            $order->nova_poshta_storage = $this->novaPoshtaStorage;
            $order->comment = $this->comment;
            $order->sum = \Yii::$app->cart->getCost();
            $order->discount_card = $this->discountCard;
//            $order->promo_code = $this->promoCode;

            if (!$order->save()) {
                $this->addErrors($order->getErrors());
                return false;
            } else {
                foreach (\Yii::$app->cart->getPositions() as $position) {
                    $orderProduct = ($position instanceof StoreProductCartPosition)
                        ? $this->createStoreProduct($position, $order)
                        : $this->createCertifcateProduct($position, $order);

                    $orderItems[] = $orderProduct;
                }
            }

            \Yii::$app->cart->removeAll();

            $transaction->commit();

            $this->sendEmail($order, $orderItems);

            //Info about order for Pokupon
            //PokuponChecker::setPokuponInfoAfterOrderDone($order->id, $order->sum);

            //StoreOrder::createOrderInRetailCrm($order->id);

            $this->createdOrder = $order;
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();

            $this->addError('name', $e->getMessage());

            return false;
        }
    }

    /**
     * @param CartPositionInterface $pos
     * @param \app\modules\store\models\StoreOrder $order
     *
     * @return StoreOrderProduct
     */
    public function createStoreProduct(CartPositionInterface $pos, \app\modules\store\models\StoreOrder $order)
    {
        /**
         * @var StoreProductCartPosition $pos
         */
        $orderProduct = new StoreOrderProduct();
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $pos->id;
        $orderProduct->sku = $pos->variant ? $pos->getVariant()['sku'] : $pos->sku;
        $orderProduct->qnt = $pos->getQuantity();
        $orderProduct->save(false);

        return $orderProduct;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getOrderDoneUrl($params = [])
    {
        return BackModel::createUrl('/store/cart/order-done', $params);
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->createdOrder;
    }

    /**
     * @return bool
     */
    public function isExternalPayment()
    {
        return $this->paymentType == \common\models\StoreOrder::PAYMENT_TYPE_VISA;
    }

    /**
     * @param $order
     * @param $orderItems
     */
    protected function sendEmail($order, $orderItems)
    {
        $adminEmails = \Yii::$app->config->get('admin_email');

        if ($adminEmails) {
            $mail = (new ViewLetter())
                ->setSubject('Новый заказ')
                ->setBodyFromView('order', [
                    'order' => $order,
                    'orderItems' => $orderItems
                ]);

            $emails = explode(',', $adminEmails);
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }

            $mail->send();
        }
    }

    protected function fillUserData()
    {
        if (!\Yii::$app->user->isGuest) {
            /**
             * @var User $user
             */
            $user = \Yii::$app->user->identity;

            $this->email = $user->email;
            $this->phone = str_replace('+380 ', '', $user->phone);
            $this->name = $user->name . ' '. $user->surname;
            //$this->discountCard = $user->discount_card;
            $this->address = $user->address;
        }
    }
}
