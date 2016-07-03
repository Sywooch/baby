<?php
/**
 * @var $product \app\modules\store\models\StoreProduct
 */
?>
<p class="wrap_price">
    <?php if ($product->old_price) { ?>
        <span class="price-old"><?= $product->getOldPrice() ?></span>
        <span class="price-new"><?= $product->getMinPriceWithCurrency() ?></span>
    <?php } else { ?>
        <span class="price"><?= $product->getMinPriceWithCurrency() ?></span>
    <?php } ?>
</p>
