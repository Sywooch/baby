<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components\paymentAbstractFactory;

/**
 * Interface IPaymentForm
 *
 * @package frontend\components\paymentAbstractFactory
 */
interface IPaymentForm
{
    /**
     * Url for requests to API
     *
     * @return mixed
     */
    public function getApiUrl();
}
