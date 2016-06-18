<?php
/**
 * Author: Pavel Naumenko
 */

namespace common\components;

use common\models\StoreOrder;
use common\models\User;
use rmrevin\yii\postman\RawLetter;

/**
 * Class RetailCrmHelper
 *
 * @package common\components
 */
class RetailCrmHelper
{
    /**
     * @return array
     */
    public static function getPaymentTypeList()
    {
        return [
            StoreOrder::PAYMENT_TYPE_CASH => 'cash',
            StoreOrder::PAYMENT_TYPE_CASH_PICKUP => 'payment-10',
            StoreOrder::PAYMENT_TYPE_VISA => 'bank-card'
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
            StoreOrder::DELIVERY_TYPE_COURIER => 'delivery-3',
            StoreOrder::DELIVERY_TYPE_NOVA_POSHTA => 'delivery-4',
            StoreOrder::DELIVERY_TYPE_PICKUP => 'delivery-2'
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

    public static function createUser($userId)
    {
        $error = '';

        /**
         * @var User $user
         */
        $user = User::findOne($userId);

        if ($user) {
            $storeUrl = \Yii::$app->config->get('retailCRMStoreUrl');
            $storeApiKey = \Yii::$app->config->get('retailCRMStoreApiKey');

            $client = new \RetailCrm\ApiClient(
                $storeUrl,
                $storeApiKey
            );

            $userInfo = [
                'externalId' => $userId.'_user',
                'firstName' => $user->name,
                'lastName' => $user->surname,
                'email' => $user->email,
                'phones' => [
                    [
                        'number' => $user->phone,
                    ]
                ],
                'address' => [
                    'text' => $user->getAddressForRetailCrm(),
                ],
                'customFields' => [
                    'chicardiComUser' => 'true',
                    'kartochka' => $user->discount_card
                ],
            ];

            try {
                $response = $client->customersCreate($userInfo);
            } catch (\RetailCrm\Exception\CurlException $e) {
                $error .= ' Сетевые проблемы. Ошибка подключения к retailCRM: ' . $e->getMessage();
            }

            if ($response->isSuccessful() && 201 === $response->getStatusCode()) {

                (new RawLetter())
                    ->setSubject('Новый пользователь создан в RetailCRM')
                    ->setBody('Пользователь успешно создан. ID пользователя в retailCRM = ' . $response->id)
                    ->addAddress('pavel@vintage.com.ua')
                    ->send();

            } else {
                $error .= ' Ошибка создания пользователя: Статус HTTP-ответа '. $response->getStatusCode();

//             получить детализацию ошибок
                if (isset($response['errors'])) {
                    foreach ($response['errors'] as $err) {
                        $error .= ' ' . $err;
                    }
                }
            }
        } else {
            $error .= ' No user found with id=' . $userId . "\n";
        }

        if (!empty($error)) {
            (new RawLetter())
                ->setSubject('Ошибка формирования пользователя в RetailCRM')
                ->setBody($error)
                ->addAddress('pavel@vintage.com.ua')
                ->send();
        }
    }

}
