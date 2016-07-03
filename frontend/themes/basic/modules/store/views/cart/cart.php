<?php
/**
 * @var $cart \yz\shoppingcart\ShoppingCart
 */
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductCartPosition;
use common\models\Currency;
use metalguardian\fileProcessor\helpers\FPM;
use yii\helpers\Url;

$cart = Yii::$app->cart;
$positions = $cart->getPositions();
$totalCost = $cart->getCost() . ' ' . Currency::getDefaultCurrencyCode();
?>
<div id="cart-page">
    <div class="breadcrumb">
        <a href="<?= Url::home() ?>"><?= \Yii::t('front', 'Home') ?></a> »
        <a href="#"><?= \Yii::t('front', 'Cart') ?></a>
    </div>
    <h1><span class="h1-top"><?= \Yii::t('front', 'Cart') ?></span></h1>
    <div class="cart-info">
        <table>
            <thead>
            <tr>
                <td class="image"><?= \Yii::t('front', 'Image') ?></td>
                <td class="name"><?= \Yii::t('front', 'Product Name') ?></td>
                <td class="model"><?= \Yii::t('front', 'Model') ?></td>
                <td class="model"><?= \Yii::t('front', 'Size') ?></td>
                <td class="quantity"><?= \Yii::t('front', 'Quantity') ?></td>
                <td class="price"><?= \Yii::t('front', 'Unit Price') ?></td>
                <td class="total"><?= \Yii::t('front', 'Total') ?></td>
            </tr>
            </thead>
            <tbody>
            <?php /** @var StoreProductCartPosition $item */
            foreach ($positions as $item):
                $product = $item->getProduct();
                ?>
                <?php $productUrl = StoreProduct::getProductUrl(['alias' => $product->alias]) ?>
                <tr>
                    <td class="image">
                        <a href="<?= $productUrl ?>">
                            <?= FPM::image($product->mainImage->file_id, 'product', 'mainPreview', [
                                'width' => 130,
                                'height' => 130
                            ]) ?>
                        </a>
                    </td>
                    <td class="name">
                        <a href="<?= $productUrl ?>"><?= $product->label ?></a>
                        <div> </div>
                    </td>
                    <td class="model"><?= $product->sku ?></td>
                    <td class="model"><?= $item->getSize()->typeSize->getLabel() ?></td>
                    <td class="quantity">
                        <input class="cart-quantity" data-url="<?= StoreProductCartPosition::getCartUpdateUrl(['id' => $item->id]) ?>" type="text" name="quantity" value="<?= $item->getQuantity() ?>" size="1">
                    </td>
                    <td class="price"><?= $item->getPrice() ?> <?= Currency::getDefaultCurrencyCode() ?></td>
                    <td class="total"><?= $item->getCost() ?> <?= Currency::getDefaultCurrencyCode() ?>
                        <div class="reload">
                            <a class="ajax-link" href="<?= $item->getRemoveUrl(true) ?>" data-item="<?= $item->id ?>">
                                <img src="/image/del.png" alt="<?= \Yii::t('front', 'Remove') ?>" title="<?= \Yii::t('front', 'Remove') ?>">
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="emptyrow"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h2 class="cart-h2"><?= \Yii::t('front', 'What would you like to do next?') ?></h2>
    <div class="cart-total">
        <table id="total">
            <tbody>
            <tr>
                <td class="right lastrow"><b><?= \Yii::t('front', 'Total:') ?></b></td>
                <td class="right last lastrow"><?= $totalCost ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="buttons">
        <div class="right"><a href="<?= StoreProductCartPosition::getCheckoutUrl() ?>" class="button"><?= \Yii::t('front', 'Checkout') ?></a></div>
        <div class="center">
            <a href="<?= Url::home() ?>" class="button"><?= \Yii::t('front', 'Continue Shopping') ?></a>
        </div>
    </div>
</div>
