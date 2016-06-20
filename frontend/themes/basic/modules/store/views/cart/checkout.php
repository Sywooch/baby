<?php
/**
 * @var $model \app\modules\store\models\StoreOrder
 */
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductCartPosition;
use common\models\Currency;
use common\models\StoreOrder;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$cart = Yii::$app->cart;
$positions = $cart->getPositions();
$totalCost = $cart->getCost() . ' ' . Currency::getDefaultCurrencyCode();
?>
<div class="breadcrumb">
    <a href="<?= Url::home() ?>"><?= \Yii::t('front', 'Home') ?></a> »
    <a href="<?= StoreProductCartPosition::getShowCartUrl() ?>"><?= \Yii::t('front', 'Cart') ?></a> »
    <a href="#"><?= \Yii::t('front', 'Checkout') ?></a>
</div>
<h1><span class="h1-top"><?= \Yii::t('front', 'Checkout') ?></span></h1>
<div class="checkout">
    <div id="checkout">
        <div class="checkout-content" style="display: block;">
            <?php $form = ActiveForm::begin([
                'id' => 'order-form',
                'fieldConfig' => [
                    'template' => '{input}{error}',
                ],
            ]); ?>
            <div class="left">
                <h2><?= \Yii::t('front', 'Your Personal Details') ?></h2>
                <span class="required">*</span> <?= \Yii::t('front', 'Name:') ?><br>
                <?= $form->field($model, 'name') ?>
                <br>
                <?= \Yii::t('front', 'E-Mail:') ?><br>
                <?= $form->field($model, 'email') ?>
                <br>
                <span class="required">*</span> <?= \Yii::t('front', 'Phone:') ?><br>
                <?= $form->field($model, 'phone') ?>
                <br>
            </div>
            <div class="right">
                <h2><?= \Yii::t('front', 'Your Address') ?></h2>
                <?= \Yii::t('front', 'City:') ?><br>
                <?= $form->field($model, 'city') ?>
                <br>
                <?= \Yii::t('front', 'Street:') ?><br>
                <?= $form->field($model, 'street') ?>
                <br>
                <?= \Yii::t('front', 'House:') ?><br>
                <?= $form->field($model, 'house') ?>
                <br>
                <?= \Yii::t('front', 'Apartment:') ?><br>
                <?= $form->field($model, 'apartment') ?>
                <br>
                <br>
            </div>
            <div class="left">
                <table class="radio">
                    <tbody>
                    <tr>
                        <td colspan="3"><h2><?= \Yii::t('front', 'Delivery Method') ?></h2></td>
                    </tr>
                    <?=
                    $form->field($model, 'deliveryType', ['template' => "{input}\n{error}"])->radioList(
                        [
                            StoreOrder::DELIVERY_TYPE_COURIER => Yii::t('frontend', 'Order_form_courier'),
                            StoreOrder::DELIVERY_TYPE_NOVA_POSHTA => Yii::t('frontend', 'Order_form_newPoshta'),
                            StoreOrder::DELIVERY_TYPE_PICKUP => Yii::t('frontend', 'Order_form_pickup')
                        ],
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return Html::tag(
                                    'tr',
                                    Html::tag('td',
                                        Html::input('radio', $name, $value, ['checked' => $checked, 'id' => 'radio_payment_'.$index, 'class' => 'radioclass']).
                                        Html::label($label, 'radio_payment_'.$index)
                                    ),
                                    ['class' => 'highlight']
                                );
                            },
                        ]
                    ); ?>
                    </tbody>
                </table>
            </div>
            <div class="right">
                <table class="radio">
                    <tbody>
                    <tr>
                        <td colspan="3"><h2><?= \Yii::t('front', 'Payment Method') ?></h2></td>
                    </tr>
                    <?=
                    $form->field($model, 'paymentType', ['template' => "{input}\n{error}"])->radioList(
                        [
                            StoreOrder::PAYMENT_TYPE_CASH => Yii::t('frontend', 'Order_form_cash'),
                            StoreOrder::PAYMENT_TYPE_CASH_PICKUP => Yii::t('frontend', 'Order_form_cash_on_delivery'),
                            StoreOrder::PAYMENT_TYPE_VISA => Yii::t('frontend', 'Order_form_visa_payment')
                        ],
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return Html::tag(
                                    'tr',
                                    Html::tag('td',
                                        Html::input('radio', $name, $value, ['checked' => $checked, 'id' => 'radio_payment_'.$index, 'class' => 'radioclass']).
                                        Html::label($label, 'radio_payment_'.$index)
                                    ),
                                    ['class' => 'highlight']
                                );
                            },
                        ]
                    ); ?>
                    </tbody>
                </table>
            </div>

            <p class="comment-p"><?= \Yii::t('front', 'Add Comments About Your Order') ?></p>
            <?= $form->field($model, 'comment')->textarea() ?>
            <br>
            <br>
            <div class="checkout-product">
                <table>
                    <thead>
                    <tr>
                        <td class="name"><?= \Yii::t('front', 'Product Name') ?></td>
                        <td class="model"><?= \Yii::t('front', 'Model') ?></td>
                        <td class="quantity"><?= \Yii::t('front', 'Quantity') ?></td>
                        <td class="price"><?= \Yii::t('front', 'Price') ?></td>
                        <td class="total"><?= \Yii::t('front', 'Total') ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var StoreProductCartPosition $item */
                    foreach ($positions as $item): ?>
                        <tr>
                            <td class="name">
                                <a href="<?= StoreProduct::getProductUrl(['alias' => $item->alias]) ?>">
                                    <?= $item->label ?>
                                </a>
                            </td>
                            <td class="model"><?= $item->sku ?></td>
                            <td class="quantity"><?= $item->getQuantity() ?></td>
                            <td class="price"><?= $item->getPrice() ?> <?= Currency::getDefaultCurrencyCode() ?></td>
                            <td class="total"><?= $item->getCost() ?> <?= Currency::getDefaultCurrencyCode() ?></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4" class="price"><b><?= \Yii::t('front', 'Total:') ?></b></td>
                        <td class="total"><?= $cart->getCost() . ' ' . Currency::getDefaultCurrencyCode() ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="payment">
                <div class="buttons">
                    <div class="right">
                        <input type="submit" value="<?= \Yii::t('front', 'Confirm Order') ?>" id="button-confirm" class="button">
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
