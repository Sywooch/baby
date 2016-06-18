<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\controllers;

use common\components\RetailCrmHelper;
use common\models\Currency;
use common\models\StoreCategory;
use common\models\StoreOrder;
use common\models\StoreProduct;
use common\models\User;
use metalguardian\fileProcessor\helpers\FPM;
use rmrevin\yii\postman\RawLetter;
use sammaye\extensions\NestedSetBehavior;
use yii\console\Controller;

/**
 * Class RetailCRMController
 *
 * @package console\controllers
 */
class RetailCrmController extends Controller
{
    /**
     * @var string
     */
    public $domain = 'http://chicardi.com';

    /**
     * @var string
     */
    public $productUrl = 'http://chicardi.com/catalog/product/';


    public function actionGenerateCatalog()
    {
        $domTree = new \DOMDocument('1.0', 'UTF-8');

        //yml_catalog
        $catalogRoot = $domTree->createElement('yml_catalog');
        $catalogRoot = $domTree->appendChild($catalogRoot);
        $catalogRoot->appendChild($domTree->createAttribute('date'));
        $catalogRoot->setAttribute('date', (new \DateTime())->format('Y-m-d H:i:s'));

        //shop
        $shop = $domTree->createElement('shop');
        $shop = $catalogRoot->appendChild($shop);

        $shopName = $domTree->createElement('name', 'Chicardi.com');
        $shopName = $shop->appendChild($shopName);

        $shopCompany = $domTree->createElement('company', 'Chicardi.com');
        $shopCompany = $shop->appendChild($shopCompany);

        $this->fillCategories($domTree, $catalogRoot);

        $this->fillOffers($domTree, $catalogRoot);

        $domTree->save(__DIR__ . '/../../frontend/web/retailcrm.xml');
        echo $domTree->saveXML();
    }

    /**
     * @param $orderId
     */
    public function actionCreateNewOrder($orderId)
    {
        $error = '';

        /**
         * @var StoreOrder $order
         */
        $order = StoreOrder::findOne($orderId);

        if ($order) {
            $storeUrl = \Yii::$app->config->get('retailCRMStoreUrl');
            $storeApiKey = \Yii::$app->config->get('retailCRMStoreApiKey');

            $client = new \RetailCrm\ApiClient(
                $storeUrl,
                $storeApiKey
            );

            $orderInfo = [
                'externalId' => $orderId . '_chic',
                'firstName' => $order->name,
                'phone' => $order->phone,
                'email' => $order->email,
                'customerComment' => $order->comment,
                'paymentType' => RetailCrmHelper::getPaymentType($order->payment_type),
                'delivery' => [
                    'code' => RetailCrmHelper::getDeliveryType($order->delivery_type),
                    'address' => [
                        'text' => $order->getDeliveryAddress()
                    ]
                ],
                'customFields' => [
                    'nomerKartochki' => $order->discount_card
                ],
                'status' => StoreOrder::getStatus($order->status),
                'items' => [],
            ];
            if ($order->user_id) {
                $orderInfo['customerId'] = $order->user_id.'_user';
            }

            foreach ($order->storeOrderProducts as $product) {
                /**
                 * @var \common\models\StoreOrderProduct $product
                 */
                if ($product->product_id) {
                    $orderInfo['items'][] = [
                        'quantity' => $product->qnt,
                        'productId' => $product->product_id,
                        'xmlId' => $product->product_id,
                    ];
                }
            }

            try {
                $response = $client->ordersCreate($orderInfo);
            } catch (\RetailCrm\Exception\CurlException $e) {
                $error .= ' Сетевые проблемы. Ошибка подключения к retailCRM: ' . $e->getMessage();
            }

            if ($response->isSuccessful() && 201 === $response->getStatusCode()) {
                echo 'Заказ успешно создан. ID заказа в retailCRM = ' . $response->id;
                // или $response['id'];
                // или $response->getId();
            } else {
                $error .= ' Ошибка создания заказа: Статус HTTP-ответа ' . $response->getStatusCode();

//             получить детализацию ошибок
                if (isset($response['errors'])) {
                    foreach ($response['errors'] as $err) {
                        $error .= ' ' . $err;
                    }
                }
            }
        } else {
            $error .= ' No order found with id=' . $orderId . "\n";
        }


        if (!empty($error)) {
            (new RawLetter())
                ->setSubject('Ошибка формирования заказа в RetailCRM')
                ->setBody($error)
                ->addAddress('pavel@vintage.com.ua')
                ->send();
        }
    }

    /**
     * @param $orderId
     */
    public function actionUpdateOrder($orderId)
    {
        $error = '';

        /**
         * @var StoreOrder $order
         */
        $order = StoreOrder::findOne($orderId);

        if ($order) {
            $storeUrl = \Yii::$app->config->get('retailCRMStoreUrl');
            $storeApiKey = \Yii::$app->config->get('retailCRMStoreApiKey');

            $client = new \RetailCrm\ApiClient(
                $storeUrl,
                $storeApiKey
            );

            $orderInfo = [
                'externalId' => $orderId . '_chic',
                'firstName' => $order->name,
                'phone' => $order->phone,
                'email' => $order->email,
                'customerComment' => $order->comment,
                'paymentType' => RetailCrmHelper::getPaymentType($order->payment_type),
                'delivery' => [
                    'code' => RetailCrmHelper::getDeliveryType($order->delivery_type),
                    'address' => [
                        'text' => $order->getDeliveryAddress()
                    ]
                ],
                'customFields' => [
                    'nomerKartochki' => $order->discount_card
                ],
                'status' => StoreOrder::getStatus($order->status),
                'items' => [],
            ];
            if ($order->user_id) {
                $orderInfo['customerId'] = $order->user_id.'_user';
            }

            foreach ($order->storeOrderProducts as $product) {
                /**
                 * @var \common\models\StoreOrderProduct $product
                 */
                if ($product->product_id) {
                    $orderInfo['items'][] = [
                        'quantity' => $product->qnt,
                        'productId' => $product->product_id,
                        'xmlId' => $product->product_id,
                    ];
                }
            }

            try {
                $response = $client->ordersEdit($orderInfo);
            } catch (\RetailCrm\Exception\CurlException $e) {
                $error .= ' Сетевые проблемы. Ошибка подключения к retailCRM: ' . $e->getMessage();
            }

            if ($response->isSuccessful() && 200 === $response->getStatusCode()) {
                echo 'Заказ успешно обновлен. ID заказа в retailCRM = ' . $response->id;
            } else {
                $error .= ' Ошибка обновления заказа: Статус HTTP-ответа ' .
                        $response->getStatusCode(). '. Номер заказа ' . $order->id;

//             получить детализацию ошибок
                if (isset($response['errors'])) {
                    foreach ($response['errors'] as $err) {
                        $error .= ' ' . $err;
                    }
                }
            }
        } else {
            $error .= ' No order found with id=' . $orderId . "\n";
        }


        if (!empty($error)) {
            (new RawLetter())
                ->setSubject('Ошибка обновления заказа в RetailCRM')
                ->setBody($error)
                ->addAddress('pavel@vintage.com.ua')
                ->send();
        }
    }

    /**
     * @param $userId
     */
    public function actionCreateNewUser($userId)
    {
        RetailCrmHelper::createUser($userId);
    }

    public function actionMigrateAllUsersToRetailCrm()
    {
        foreach (User::find()->each() as $user) {
            RetailCrmHelper::createUser($user->id);
            echo "\n";
        }
    }

    /**
     * @param $userId
     */
    public function actionUpdateUser($userId)
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
                    'kartochka' => $user->discount_card
                ],
            ];

            try {
                $response = $client->customersEdit($userInfo);
            } catch (\RetailCrm\Exception\CurlException $e) {
                $error .= ' Сетевые проблемы. Ошибка подключения к retailCRM: ' . $e->getMessage();
            }

            if ($response->isSuccessful() && 200 === $response->getStatusCode()) {
                echo 'Данные пользователя обновлены. ID пользователя в retailCRM = ' . $response->id;
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
                ->setSubject('Ошибка обновления личных данных пользователя в RetailCRM')
                ->setBody($error)
                ->addAddress('pavel@vintage.com.ua')
                ->send();
        }
    }

    /**
     * @param \DOMDocument $domTree
     * @param \DOMElement $catalogRoot
     */
    protected function fillCategories(\DOMDocument $domTree, \DOMElement $catalogRoot)
    {
        $categories = $domTree->createElement('categories');
        $categories = $catalogRoot->appendChild($categories);

        foreach (StoreCategory::find()->where('label != "root"')->orderBy('lft')->each(50) as $cat) {
            /**
             * @var StoreCategory $cat
             */
            $cat->attachBehavior('ns', NestedSetBehavior::className());
            $oneCat = $domTree->createElement('category', $cat->label);
            $oneCat = $categories->appendChild($oneCat);

            $oneCat->appendChild($domTree->createAttribute('id'));
            $oneCat->setAttribute('id', $cat->id);

            $parent = $cat->parent()->one();
            if ($parent && $parent->label != 'root') {
                $oneCat->appendChild($domTree->createAttribute('parentId'));
                $oneCat->setAttribute('parentId', $parent->id);
            }
        }
    }

    /**
     * @param \DOMDocument $domTree
     * @param \DOMElement $catalogRoot
     */
    protected function fillOffers(\DOMDocument $domTree, \DOMElement $catalogRoot)
    {
        $offers = $domTree->createElement('offers');
        $offers = $catalogRoot->appendChild($offers);

        foreach (StoreProduct::find()->each(50) as $product) {
            /**
             * @var StoreProduct $product
             */
            $oneProduct = $domTree->createElement('offer');
            $oneProduct = $offers->appendChild($oneProduct);

            $oneProduct->appendChild($domTree->createAttribute('id'));
            $oneProduct->setAttribute('id', $product->id);
            $oneProduct->appendChild($domTree->createAttribute('productId'));
            $oneProduct->setAttribute('productId', $product->id);


            $url = $domTree->createElement('url', $this->productUrl . $product->alias);
            $oneProduct->appendChild($url);

            $price = $domTree->createElement(
                'price',
                number_format(Currency::getPriceInCurrency($product->price), 2, '.', '')
            );
            $oneProduct->appendChild($price);

            $categoryId = $domTree->createElement('categoryId', $product->category_id);
            $oneProduct->appendChild($categoryId);

            $image = $product->image;

            if ($image) {
                $picture = $domTree->createElement(
                    'picture',
                    $this->domain . FPM::originalSrc($product->image->file_id)
                );
                $oneProduct->appendChild($picture);
            }

            $pName = htmlspecialchars(str_replace(['»', '«'], '', $product->label));

            $name = $domTree->createElement('name', $pName);
            $oneProduct->appendChild($name);

            $productName = $domTree->createElement('productName', $pName);
            $oneProduct->appendChild($productName);

            //params
            $paramArticle = $domTree->createElement('param', $product->sku);
            $oneProduct->appendChild($paramArticle);
            $paramArticle->appendChild($domTree->createAttribute('name'));
            $paramArticle->setAttribute('name', 'Артикул');
            $paramArticle->appendChild($domTree->createAttribute('code'));
            $paramArticle->setAttribute('code', 'article');

            $paramDesc = $domTree->createElement('param', $product->announce);
            $oneProduct->appendChild($paramDesc);
            $paramDesc->appendChild($domTree->createAttribute('name'));
            $paramDesc->setAttribute('name', 'Описание');
            $paramDesc->appendChild($domTree->createAttribute('code'));
            $paramDesc->setAttribute('code', 'description');

            foreach ($product->eav as $eavModel) {
                $param = $domTree->createElement('param', $eavModel->getAttributeValue());
                $oneProduct->appendChild($param);
                $param->appendChild($domTree->createAttribute('name'));
                $param->setAttribute('name', $eavModel->attributeRel->label);
                $param->appendChild($domTree->createAttribute('code'));
                $param->setAttribute('code', $eavModel->attributeRel->alias);
            }
        }
    }
}
