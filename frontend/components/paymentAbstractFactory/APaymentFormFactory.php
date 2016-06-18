<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components\paymentAbstractFactory;

/**
 * Class APaymentFormFactory
 *
 * @package frontend\components\paymentAbstractFactory
 */
abstract class APaymentFormFactory
{
    /**
     * Return filled form for payment request
     *
     * @param $payment
     * @param $data
     *
     * @return mixed
     */
    abstract public function createPaymentForm($payment, $data = []);

    /**
     * Return form for validating data, sent from API sever
     *
     * @param $resultData
     *
     * @return mixed
     */
    abstract public function createPaymentResultForm($resultData);
}
