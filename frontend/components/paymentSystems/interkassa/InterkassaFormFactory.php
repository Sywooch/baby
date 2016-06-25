<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components\paymentSystems\interkassa;

use frontend\components\paymentAbstractFactory\APaymentFormFactory;

/**
 * Class InterkassaFormFactory
 *
 * @package product\components\paymentSystems\interkassa
 */
class InterkassaFormFactory extends APaymentFormFactory
{
    /**
     * @inheritdoc
     */
    public function createPaymentForm($payment, $data = [])
    {
        $iForm = new InterkassaPaymentForm();
        $iForm->ik_co_id = \Yii::$app->params['interkassaID'];
        $iForm->ik_am = $payment->sum_uah;
        $iForm->ik_pm_no = $payment->id;
        $iForm->ik_desc = 'Оплата заказа';
        if (isset($data['orderId'])) {
            $iForm->ik_desc = ' ' . $data['orderId'];
        }
        $iForm->generateSignature();

        return $iForm;
    }

    /**
     * @inheritdoc
     */
    public function createPaymentResultForm($resultData)
    {
        $form = new InterkassaPaymentResultForm();
        $form->setAttributes($resultData);

        return $form;
    }
}
