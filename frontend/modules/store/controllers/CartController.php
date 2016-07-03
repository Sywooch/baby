<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\controllers;

use app\models\Payment;
use app\modules\store\forms\OrderForm;
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductCartPosition;
use common\models\StoreOrder;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use frontend\modules\common\models\PageSeo;
use frontend\modules\store\models\StoreProductSize;
use frontend\widgets\headerCart\Widget;
use Imagine\Exception\InvalidArgumentException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class CartController
 * @package app\modules\store\controllers
 */
class CartController extends FrontController
{
    /**
     * @var null|string
     */
    public $redirectAfterOrderUrl = null;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'paramsFilter' => [
                'class' => UnusedParamsFilter::className(),
                'actions' => [
                    //action => ['param', 'param2']
                    'add' => ['sku', 'id', 'sizeId', 'type', 'quantity'],
                    'remove' => ['sku', 'id', 'all', 'type'],
                    'update' => ['id', 'quantity'],
                    'show-cart' => ['orderCreateRequest'],
                    'order-done' => ['hash']
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                    'remove' => ['post'],
                    'get-small-cart' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * @return string
     */
    public function actionGetSmallCart()
    {
        return $this->renderCart();
    }

    /**
     * @param $id
     * @param $sizeId
     * @param string $type
     *
     * @return string
     */
    public function actionAdd($id, $type = 'product')
    {
        $sizeId = \Yii::$app->request->get('sizeId');
        if ($type == 'product') {
            $productPosition = new StoreProductCartPosition($id, $sizeId);
            if (\Yii::$app->cart->hasPosition($productPosition->getId())) {

                $productPosition = \Yii::$app->cart->getPositionById($productPosition->getId());
            }
        } else {
            throw new InvalidArgumentException;
        }

        $quantity = \Yii::$app->request->get('quantity');
        $quantity = $quantity > 1 ? (int)$quantity : 1;
        \Yii::$app->cart->put($productPosition, $quantity);

        return $this->renderCart(true);
    }

    /**
     * @param $id int
     *
     * @return string
     * @internal param string $type
     *
     */
    public function actionUpdate($id)
    {
        $productPosition = \Yii::$app->cart->getPositionById($id);
        if ($productPosition) {
            $quantity = \Yii::$app->request->get('quantity');
            $quantity = $quantity > 1 ? (int)$quantity : 1;
            \Yii::$app->cart->update($productPosition, $quantity);
        }

        return $this->renderCart(true);
    }

    /**
     * @param $id
     * @param bool $all
     *
     * @return string
     */
    public function actionRemove($id, $all = false)
    {
        $type = \Yii::$app->request->get('type');

        if (!$type || $type == 'product') {
            $productPosition = \Yii::$app->cart->getPositionById($id);\common\helpers\Dump::dump($productPosition);
            if ($productPosition) {
                \Yii::$app->cart->update($productPosition, $all ? 0 : ($productPosition->getQuantity()-1));
            }
        } else {
            throw new InvalidArgumentException;
        }

        return $this->renderCart();
    }

    /**
     * @return array|string|Response
     */
    public function actionShowCart()
    {
        $this->modelToFetchSeo = PageSeo::findOne(9);

        return $this->render('cart');
    }

    /**
     * @param $hash
     *
     * @return string
     * @throws Exception
     */
    public function actionOrderDone($hash)
    {
        /*$order = \Yii::$app->session->hasFlash('order')
            ? Json::decode(\Yii::$app->session->getFlash('order'))
            : false;*/

        $this->modelToFetchSeo = PageSeo::findOne(2);
        $id = base64_decode($hash);
        $id = str_replace(\Yii::$app->params['orderSalt'], '', $id);
        $order = StoreOrder::findOne($id);
        if (!$order) {
            throw new HttpException(404, \Yii::t('front', 'Can\'t find order'));
        }

        return $this->render('order_done', compact('order'));
    }

    /**
     * @param bool $showCart
     *
     * @return string
     */
    protected function renderCart($showCart = false)
    {
        $data = [
                'replaces' => [
                    [
                        'data' => Widget::widget(),
                        'what' => '#cart'
                    ],
                    [
                        'data' => $this->renderAjax('cart'),
                        'what' => '#cart-page'
                    ],
                ],
            ];
        if (\Yii::$app->controller->action->id == 'add') {
            $data['replaces'][] = [
                'data' => $this->renderAjax('success-add-popup'),
                'what' => '#popup'
            ];
        }

        return Json::encode($data);
    }

    /**
     * @param OrderForm $form
     *
     * @return string|Response
     */
    protected function createOrder(OrderForm &$form)
    {
        if ($form->load(\Yii::$app->request->post()) && $form->validate() && $form->save()) {
            $order = $form->getOrder();

            \Yii::$app->session->setFlash('order', Json::encode([
                'id' => $order->id,
                'sum' => $order->sum
            ]));
            $hash = base64_encode(\Yii::$app->params['orderSalt'] . $order->id);

            $redirectUrl = $form->isExternalPayment()
                ? Payment::getPayUrl(['orderId' => $order->id])
                : OrderForm::getOrderDoneUrl(['hash' => $hash]);
            $this->redirectAfterOrderUrl = $redirectUrl;

            return \Yii::$app->request->isAjax
                ? $this->renderCart()
                : $this->redirect($redirectUrl);
        }

        return false;
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function actionCheckout()
    {
        $this->modelToFetchSeo = PageSeo::findOne(12);

        if (\Yii::$app->cart->isEmpty) {
            throw new HttpException('404', 'Корзина пуста, сперва добавте товар.');
        }
        $form = new OrderForm();

        if (\Yii::$app->request->isAjax && $form->load(\Yii::$app->request->post())) {
            $errors = ActiveForm::validate($form);
            if ($errors) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                return ActiveForm::validate($form);
            }
        }

        $order = $this->createOrder($form);
        if ($order) {
            return $order;
        }

        return $this->render('checkout', ['model' => $form]);
    }
}
