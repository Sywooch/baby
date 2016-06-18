<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\controllers;

use app\models\Payment;
use app\models\PaymentLog;
use app\modules\store\models\StoreOrder;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use yii\web\HttpException;

/**
 * Class PaymentController
 *
 * @package frontend\modules\store\controllers
 */
class PaymentController extends FrontController
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'paramsFilter' => [
                'class' => UnusedParamsFilter::className(),
                'actions' => [
                    'pay' => ['orderId'],
                ]
            ],
        ];
    }

    /**
     * @param $orderId
     *
     * @return string
     * @throws HttpException
     */
    public function actionPay($orderId)
    {
        $this->layout = false;

        $order = StoreOrder::find()
            ->andWhere('id = :id', [':id' => $orderId])
            ->andWhere(['status' => \common\models\StoreOrder::STATUS_NEW])
            ->one();

        if (!$order) {
            throw new HttpException(404, \Yii::t('frontend', 'Order not found'));
        }

        $payment = Payment::createPayment($order);

        if (!$payment) {
            throw new HttpException(404, \Yii::t('frontend', 'Unreachable payment'));
        }

        return $this->render('pay', ['form' => $payment->getPaymentForm(['orderId' => $payment->order_id])]);
    }

    /**
     * @return string
     */
    public function actionValidate()
    {
        $validPaymentId = Payment::validatePayment(\Yii::$app->request->post());

        if ($validPaymentId) {
            /**
             * @var Payment $payment
             */
            $payment = Payment::find()->andWhere(
                'id = :id AND status = :st',
                [
                    ':st' => Payment::STATUS_WAIT_FOR_CONFIRM,
                    ':id' => (int)$validPaymentId
                ]
            )->one();
            if ($payment) {
                if (!$payment->makePaymentPaid()) {
                    PaymentLog::add('Не получилось изменить статус платежа '.$payment->id);
                    return false;
                }

                return true;
            } else {
                PaymentLog::add('Не найден платеж #' . $validPaymentId . ' для подтверждения.');

                return false;
            }
        }
    }

    /**
     * @return string
     */
    public function actionSuccessPayment()
    {
        $this->layout = '//simple';

        return $this->render('success');
    }

    /**
     * @return string
     */
    public function actionFailedPayment()
    {
        $this->layout = '//simple';

        return $this->render('fail');
    }
}
