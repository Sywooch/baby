<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use yii\helpers\Html;
use yii\web\Cookie;

/**
 * Class PokuponChecker
 *
 * @package frontend\components
 */
class PokuponChecker
{
    public static function checkUrl()
    {
        $pokuponActionId = \Yii::$app->request->get('pokupon_cid');
        $pokuponUserId = \Yii::$app->request->get('pokupon_uid');

        if ($pokuponActionId && $pokuponUserId) {
            $cookies = \Yii::$app->response->cookies;

            $cookies->add(
                new Cookie(
                    [
                        'name' => 'pokupon_cid',
                        'expire' => strtotime('+ 30 days'),
                        'value' => $pokuponActionId,
                    ]
                )
            );
            $cookies->add(
                new Cookie(
                    [
                        'name' => 'pokupon_uid',
                        'expire' => strtotime('+ 30 days'),
                        'value' => $pokuponUserId,
                    ]
                )
            );
        }
    }

    /**
     * @param $orderId
     * @param $orderAmount
     */
    public static function setPokuponInfoAfterOrderDone($orderId, $orderAmount)
    {
        $cookies = \Yii::$app->response->cookies;

        $cookies->add(
            new Cookie(
                [
                    'name' => 'last_order_id',
                    'expire' => strtotime('+ 5 minutes'),
                    'value' => $orderId,
                ]
            )
        );

        $cookies->add(
            new Cookie(
                [
                    'name' => 'last_order_amount',
                    'expire' => strtotime('+ 5 minutes'),
                    'value' => $orderAmount,
                ]
            )
        );
        return \Yii::$app->response;
    }

    /**
     * @return null|string
     */
    public static function getPokuponImg()
    {
        $cookies = \Yii::$app->request->cookies;
        $pokuponActionId = $cookies->get('pokupon_cid');
        $pokuponUserId = $cookies->get('pokupon_uid');
        $pokuponOrderId = $cookies->get('last_order_id');
        $pokuponOrderAmount = $cookies->get('last_order_amount');

        if ($pokuponActionId && $pokuponUserId && $pokuponOrderId && $pokuponOrderAmount) {
            $src = "http://pokupon.ua/pixel/$pokuponActionId/new.jpg?uid=$pokuponUserId&ord_id=$pokuponOrderId&amount=$pokuponOrderAmount";

            return Html::img($src);
        }

        return null;
    }
}
