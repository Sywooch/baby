<?php
/**
 * @var $sizes \frontend\modules\store\models\StoreProductSize[]
 */

?>
<div class="sort">
    <label><?= \Yii::t('front', 'Sizes:') ?></label>
    <select class="selectBox product-sizes">
        <?php foreach ($sizes as $key => $size): ?>
            <option 
                value="<?= $size->id ?>" <?= !$key ? 'selected="selected"' : '' ?>
                data-price="<?= $size->getPriceWithCurrency() ?>"
                data-oldprice="<?= $size->getOldPriceInCurrency() ?>"
            >
                <?= $size->typeSize->getLabel() ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
