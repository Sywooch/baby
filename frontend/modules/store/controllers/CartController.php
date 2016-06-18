<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\controllers;

use app\models\Payment;
use app\modules\certificate\models\Certificate;
use app\modules\store\forms\OrderForm;
use app\modules\store\models\StoreProductCartPosition;
use common\models\StoreProduct;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use Imagine\Exception\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
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
                    'add' => ['sku', 'id', 'type'],
                    'remove' => ['sku', 'id', 'all', 'type'],
                    'show-cart' => ['orderCreateRequest']
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
     * @param string $type
     *
     * @return string
     */
    public function actionAdd($id, $type = 'product')
    {
        if ($type == 'product') {
            $product = StoreProductCartPosition::find()
                ->where(['visible' => 1])
                ->andWhere(['status' => StoreProduct::STATUS_AVAILABLE])
                ->andWhere('id = :id', [':id' => $id])
                ->one();

            $variantSku = \Yii::$app->request->get('sku');

            if ($variantSku) {
                $product->setVariant($variantSku);
            }
        } elseif ($type == 'cert') {
            $product = Certificate::find()
                ->where(['visible' => 1])
                ->andWhere('id = :id', [':id' => $id])
                ->one();
        } else {
            throw new InvalidArgumentException;
        }

        if ($product) {
            \Yii::$app->cart->put($product, 1);
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
            $product = StoreProductCartPosition::find()
                ->where(['visible' => 1])
                ->andWhere(['status' => StoreProduct::STATUS_AVAILABLE])
                ->andWhere('id = :id', [':id' => $id])
                ->one();
        } elseif ($type == 'cert') {
            $product = Certificate::find()
                ->where(['visible' => 1])
                ->andWhere('id = :id', [':id' => $id])
                ->one();
        } else {
            throw new InvalidArgumentException;
        }



        if ($product) {
            $variantSku = \Yii::$app->request->get('sku');

            if ($variantSku) {
                $product->setVariant($variantSku);
            }

            $position = \Yii::$app->cart->getPositionById($product->getId());

            if ($position) {
                \Yii::$app->cart->update($position, $all ? 0 : ($position->getQuantity()-1));
            }
        }


        return $this->renderCart();
    }

    /**
     * @param bool $orderCreateRequest
     *
     * @return array|string|Response
     */
    public function actionShowCart($orderCreateRequest = false)
    {
        $form = new OrderForm();

        if (!$orderCreateRequest && \Yii::$app->request->isAjax && $form->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        $order = $this->createOrder($form);
        if ($order) {
            return $order;
        }

        return $this->render('cart', ['form' => $form]);
    }

    /**
     * @return string
     */
    public function actionOrderDone()
    {
        $this->layout = '//simple';

        $order = \Yii::$app->session->hasFlash('order')
            ? Json::decode(\Yii::$app->session->getFlash('order'))
            : false;

        return $this->render('order_done', compact('order'));
    }

    /**
     * @param bool $showCart
     *
     * @return string
     */
    protected function renderCart($showCart = false)
    {
        $totalPrice = \Yii::$app->cart->getCost();
        $totalProducts = count(\Yii::$app->cart->getPositions());
        //link to redirect after order video has been shown
        $redirectUrl = is_null($this->redirectAfterOrderUrl)
            ? ''
            : Html::script(
                'modifyCartVideoSkipUrl("' . $this->redirectAfterOrderUrl . '");'
            );

        $data = [
                'content' => [
                    [
                        'data' => $this->renderPartial('small_cart'),
                        'what' => '.busket-w'
                    ],
                    [
                        'data' => $this->renderPartial('_main_cart_header'),
                        'what' => '.buscket-w .main-cart-header'
                    ],
                    [
                        'data' => $totalProducts ? $totalProducts : \Yii::t('frontend', 'cart'),
                        'what' => '.btn-top-buscket span'
                    ],
                    [
                        'data' => $totalPrice,
                        'what' => '.total-sum p b, .total-sum-confirm b'
                    ],
                    [
                        'data' => $this->renderPartial('cart_button'),
                        'what' => '.popup-cart'
                    ]
                ],
                'replaces' => [
                    [
                        'data' => $this->renderPartial('_cart_positions'),
                        'what' => '.buscket-w .purchase'
                    ],
                    [
                        'data' => StoreProductCartPosition::getShowCartButton(),
                        'what' => 'a.btn-top-buscket.btn-round.btn-round__yell'
                    ]

                ],
                'js' => $showCart ? Html::script('showSmallCart();') : $redirectUrl
            ];
        if (!$totalProducts) {
            //Remove order form if no product
            $data['content'][] = [
                'data' => '',
                'what' => '.ordering-w'
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

            $redirectUrl = $form->isExternalPayment()
                ? Payment::getPayUrl(['orderId' => $order->id])
                : OrderForm::getOrderDoneUrl();
            $this->redirectAfterOrderUrl = $redirectUrl;

            return \Yii::$app->request->isAjax
                ? $this->renderCart()
                : $this->redirect($redirectUrl);
        }

        return false;
    }
}
