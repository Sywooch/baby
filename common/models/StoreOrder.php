<?php

namespace common\models;

use common\models\StoreOrderProduct;
use vova07\console\ConsoleRunner;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%store_order}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $nova_poshta_storage
 * @property string $discount_card
 * @property string $promo_code
 * @property string $comment
 * @property integer $payment_type
 * @property integer $delivery_type
 * @property integer $delivery_time
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class StoreOrder extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_DONE = 2;
    const STATUS_DECLINED = 3;

    const PAYMENT_TYPE_CASH = 1;
    const PAYMENT_TYPE_CASH_PICKUP = 2;
    const PAYMENT_TYPE_VISA = 3;

    const DELIVERY_TYPE_COURIER = 1;
    const DELIVERY_TYPE_NOVA_POSHTA = 2;
    const DELIVERY_TYPE_PICKUP = 3;

    const DELIVERY_TIME_ANY = 0;
    const DELIVERY_TIME_MORNING = 1;
    const DELIVERY_TIME_DINNER = 2;
    const DELIVERY_TIME_EVENING = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_order}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreOrderProducts()
    {
        return $this->hasMany(StoreOrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * @return mixed|string
     */
    public function getDeliveryAddress()
    {
        if ($this->delivery_type === static::DELIVERY_TYPE_COURIER) {
            return $this->address;
        } elseif ($this->delivery_type === static::DELIVERY_TYPE_NOVA_POSHTA) {
            return $this->nova_poshta_storage;
        } else {
            return '';
        }
    }

    /**
     * @return array
     */
    public static function getPaymentTypeList()
    {
        return [
            static::PAYMENT_TYPE_CASH => 'Наличные',
            static::PAYMENT_TYPE_CASH_PICKUP => 'Наложенный платеж',
            static::PAYMENT_TYPE_VISA => 'Interkassa'
        ];
    }

    /**
     * @param $id
     *
     * @return string|null
     */
    public static function getPaymentType($id)
    {
        $list = static::getPaymentTypeList();

        return $list[$id] ? $list[$id] : null;
    }

    /**
     * @return array
     */
    public static function getDeliveryTypeList()
    {
        return [
            static::DELIVERY_TYPE_COURIER => 'Курьер',
            static::DELIVERY_TYPE_NOVA_POSHTA => 'Новая почта',
            static::DELIVERY_TYPE_PICKUP => 'Самовывоз'
        ];
    }

    /**
     * @param $id
     *
     * @return string|null
     */
    public static function getDeliveryType($id)
    {
        $list = static::getDeliveryTypeList();

        return $list[$id] ? $list[$id] : null;
    }

    /**
     * @return array
     */
    public static function getDeliveryTimeList()
    {
        return [
            static::DELIVERY_TIME_ANY => 'Любое',
            static::DELIVERY_TIME_MORNING => 'Утром',
            static::DELIVERY_TIME_DINNER => 'Днем',
            static::DELIVERY_TIME_EVENING => 'Вечером'
        ];
    }

    /**
     * @param $id
     *
     * @return string|null
     */
    public static function getDeliveryTime($id)
    {
        $list = static::getDeliveryTimeList();

        return $list[$id] ? $list[$id] : null;
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_NEW => 'Новый',
            static::STATUS_PROCESSING => 'Обрабатываеться',
            static::STATUS_DONE => 'Обработан',
            static::STATUS_DECLINED => 'Отменен'
        ];
    }

    /**
     * @param $id
     *
     * @return string|null
     */
    public static function getStatus($id)
    {
        $list = static::getStatusList();

        return $list[$id] ? $list[$id] : null;
    }

    /**
     * @return int|string
     */
    public static function getNewRequestCount()
    {
        $orderCount = (new Query())
            ->from(StoreOrder::tableName())
            ->where(['status' => StoreOrder::STATUS_NEW])
            ->count();

        $callbackCount = (new Query())
            ->from(Callback::tableName())
            ->where(['status' => Callback::STATUS_NEW])
            ->count();

        $giftRequestCount = (new Query())
            ->from(GiftRequest::tableName())
            ->where(['status' => GiftRequest::STATUS_NEW])
            ->count();

        return $orderCount + $callbackCount + $giftRequestCount;
    }

    /**
     *
     */
    public static function fireNewRequestEvent()
    {
        $pusherParam = Yii::$app->params['pusher'];
        $app_id = $pusherParam['id'];
        $app_key = $pusherParam['key'];
        $app_secret = $pusherParam['secret'];

        $pusher = new \Pusher($app_key, $app_secret, $app_id, array( 'encrypted' => true ));

        $data['message'] = static::getNewRequestCount();
        $pusher->trigger('order_count_channel', 'new_order', $data);
    }

    /**
     * @param $orderId
     */
    public static function createOrderInRetailCrm($orderId)
    {
        $consoleRunner = new ConsoleRunner(['file' => '@app/../yii']);
        $consoleRunner->run('retail-crm/create-new-order ' . $orderId);
    }

    /**
     * @param $orderId
     */
    public static function updateOrderInRetailCrm($orderId)
    {
        $consoleRunner = new ConsoleRunner(['file' => '@app/../yii']);
        $consoleRunner->run('retail-crm/update-order ' . $orderId);
    }
}
