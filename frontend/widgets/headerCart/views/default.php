<?php
/**
 * @var $cart \yz\shoppingcart\ShoppingCart
 */
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductCartPosition;
use common\models\Currency;
use metalguardian\fileProcessor\helpers\FPM;

$cart = Yii::$app->cart;
$positions = $cart->getPositions();
$totalCost = $cart->getCost() . ' ' . Currency::getDefaultCurrencyCode();
?>
<div id="cart">
    <div class="heading">
        <div class="cart_top_in">
            <h4><?= \Yii::t('front', 'Cart') ?></h4>
            <a>
                <span id="cart-total">
                    <?= $cart->getCount() ?> <?= \Yii::t('front', 'item(s)') ?> - <?= $totalCost ?>
                </span>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="mini-cart-info">
            <table>
                <tbody>
                <?php /** @var StoreProductCartPosition $item */
                foreach ($positions as $item): ?>
                    <?php $productUrl = StoreProduct::getProductUrl(['alias' => $item->alias]) ?>
                    <tr>
                        <td class="image">
                            <a href="<?= $productUrl ?>">
                                <?= FPM::image($item->mainImage->file_id, 'product', 'mainPagePreview') ?>
                            </a>
                        </td>
                        <td class="name">
                            <a href="<?= $productUrl ?>"><?= $item->label ?></a>
                            <div> </div>
                        </td>
                        <td class="quantity">x&nbsp;<?= $item->getQuantity() ?></td>
                        <td class="total"><?= $item->getCost() ?> <?= Currency::getDefaultCurrencyCode() ?></td>
                        <td class="remove">
                            <a class="ajax-link" href="<?= $item->getRemoveUrl(true) ?>">
                                <img src="/image/remove-small.png" alt="<?= \Yii::t('front', 'Remove') ?>" title="<?= \Yii::t('front', 'Remove') ?>">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mini-cart-total">
            <table>
                <tbody>
                <tr class="last_item">
                    <td class="right"><b><?= \Yii::t('front', 'Total:') ?></b></td>
                    <td class="right"><?= $totalCost ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php if (!$cart->isEmpty) { ?>
            <div class="checkout">
                <a class="button mr" href="<?= StoreProductCartPosition::getShowCartUrl() ?>"><?= \Yii::t('front', 'View Cart') ?></a>
                <a class="button" href="<?= StoreProductCartPosition::getCheckoutUrl() ?>"><?= \Yii::t('front', 'Checkout') ?></a>
            </div>
        <?php } ?>
    </div>
</div>

