<?php

namespace app\models;

use app\modules\store\models\StoreOrder;
use frontend\components\FrontModel;
use frontend\components\paymentSystems\interkassa\InterkassaFormFactory;
use Yii;
use yii\web\User;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property string $sum
 * @property string $sum_uah
 * @property string $comment
 * @property integer $status
 * @property string $created
 * @property string $modified
 *
 * @property StoreOrder $order
 * @property User $user
 */
class Payment extends FrontModel
{
    const STATUS_WAIT_FOR_CONFIRM = 0;
    const STATUS_CONFIRMED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sum', 'sum_uah', 'created', 'modified'], 'required'],
            [['user_id', 'order_id', 'status'], 'integer'],
            [['sum', 'sum_uah'], 'number'],
            [['comment'], 'string'],
            [['created', 'modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'sum' => 'Цена',
            'sum_uah' => 'Цена в гривне',
            'comment' => 'Комментарий',
            'status' => 'Статус',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(StoreOrder::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getPayUrl($params = [])
    {
        return self::createUrl('/store/payment/pay', $params);
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function getSuccessUrl($params = [])
    {
        return self::createUrl('/store/payment/success-payment', $params);
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function getFailUrl($params = [])
    {
        return self::createUrl('/store/payment/failed-payment', $params);
    }

    /**
     * @param array $data
     *
     * @return \frontend\components\paymentSystems\interkassa\InterkassaPaymentForm|mixed
     */
    public function getPaymentForm($data = [])
    {
        return (new InterkassaFormFactory())->createPaymentForm($this, $data);
    }

    /**
     * @param StoreOrder $order
     *
     * @return Payment|bool
     */
    public static function createPayment(StoreOrder $order)
    {
        $payment = new self();
        $payment->order_id = $order->id;
        $payment->user_id = Yii::$app->user->isGuest ? null : Yii::$app->user->id;
        $payment->sum = $payment->sum_uah = $order->sum;
        $payment->comment = 'Оплата заказа ' . $order->id;
        $payment->created = $payment->modified = date('Y-m-d H:i:s');
        $payment->status = static::STATUS_WAIT_FOR_CONFIRM;

        if ($payment->save(false)) {
            return $payment;
        }

        return false;
    }

    /**
     * @return $this|bool
     */
    public function makePaymentPaid()
    {
        $this->modified = date('Y-m-d H:i:s');
        $this->status = static::STATUS_CONFIRMED;

        if ($this->save(false)) {
            $this->order->payment_status = Payment::STATUS_CONFIRMED;
            $this->order->save(false);

            return $this;
        }

        return false;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public static function validatePayment(array $data)
    {
        PaymentLog::add('Новое обращение от Interkassa. Полученные данные:' . json_encode($data));

        $form = (new InterkassaFormFactory())->createPaymentResultForm($data);
        if ($form->validate()) {
            return $form->getPaymentId();
        } else {
            $text = 'Запрос от Interkassa не прошел валидацию. Ошибки валидации:<br />';
            foreach ($form->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        $text .= $error . "\n";
                    }
                }
            }
            PaymentLog::add($text);

            return false;
        }
    }
}
