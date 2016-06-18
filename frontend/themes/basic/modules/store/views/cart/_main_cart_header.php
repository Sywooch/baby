<?php
/**
 * Author: Pavel Naumenko
 */
$cart = Yii::$app->cart;
$isEmpty = $cart->getIsEmpty();
$differentProductsCount = count($cart->getPositions());
?>
<p class="<?= $isEmpty ? 'main-title main-title__hide': 'main-title' ?>"><?= Yii::t('frontend', 'cart. You choose <span><b>{count, plural, =1{1</b></span> product} other{#</b> different</span> products}}', [
            'count' => $differentProductsCount
        ]) ?></p>
<p class="<?= $isEmpty ? 'main-title': 'main-title main-title__hide' ?>"><?= Yii::t('frontend', 'your cart is empty'); ?></p>
