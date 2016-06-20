<?php
/**
 * @var $order StoreOrder
 */
use common\models\Currency;
use common\models\StoreOrder;
use yii\helpers\Url;
?>
<div class="breadcrumb">
    <a href="<?= Url::home() ?>"><?= \Yii::t('front', 'Home') ?></a> »
    <a href="#"><?= \Yii::t('front', 'Order Information') ?></a>
</div><h1><span class="h1-top"><?= \Yii::t('front', 'Thank you for order!') ?></span></h1>
<div class="information_content">
    <table class="list">
        <thead>
        <tr>
            <td class="left" colspan="2"><?= \Yii::t('front', 'Order Details') ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="left" style="width: 50%;"><b><?= \Yii::t('front', 'Order ID:') ?></b> #<?= $order->id ?><br>
                <b><?= \Yii::t('front', 'Date Added:') ?></b> <?= date('d.m.Y H:i', strtotime($order->created)) ?>
            </td>
            <td class="left" style="width: 50%;"><b><?= \Yii::t('front', 'Payment Method') ?>:</b> <?= StoreOrder::getPaymentType($order->payment_type) ?><br>
                <b><?= \Yii::t('front', 'Delivery Method') ?>:</b> <?= StoreOrder::getDeliveryType($order->delivery_type) ?>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="list">
        <thead>
        <tr>
            <td class="left"><?= \Yii::t('front', 'Delivery Address') ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="left">
                <?= $order->address ?>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="list">
        <thead>
        <tr>
            <td class="left"><?= \Yii::t('front', 'Product Name') ?></td>
            <td class="left"><?= \Yii::t('front', 'Model') ?></td>
            <td class="right"><?= \Yii::t('front', 'Quantity') ?></td>
            <td class="right"><?= \Yii::t('front', 'Price') ?></td>
            <td class="right"><?= \Yii::t('front', 'Total') ?></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order->storeOrderProducts as $orderProduct): ?>
            <tr>
                <td class="left"><?= $orderProduct->product->label ?></td>
                <td class="left"><?= $orderProduct->sku ?></td>
                <td class="right"><?= $orderProduct->qnt ?></td>
                <td class="right"><?= $orderProduct->product->price ?> <?= Currency::getDefaultCurrencyCode() ?></td>
                <td class="right"><?= $orderProduct->product->price * $orderProduct->qnt ?> <?= Currency::getDefaultCurrencyCode() ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3"></td>
            <td class="right"><b><?= \Yii::t('front', 'Total:') ?></b></td>
            <td class="right"><?= $order->sum ?> <?= Currency::getDefaultCurrencyCode() ?></td>
        </tr>
        </tfoot>
    </table>
    <table class="list">
        <thead>
        <tr>
            <td class="left"><?= \Yii::t('front', 'Order Comment') ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="left"><?= $order->comment ?></td>
        </tr>
        </tbody>
    </table>
</div>
