<?php
use app\modules\store\models\StoreProductCartPosition;
?>
<div class="popup box-content">
    <h2><?= Yii::t('front', 'Product have been added to cart!') ?></h2>
    <a class="to-cart" href="<?= StoreProductCartPosition::getShowCartUrl() ?>"><?= \Yii::t('front', 'To Cart') ?></a>
    <a href="#" class="close-fancybox continue-shopping"><?= \Yii::t('front', 'Continue shopping') ?></a>
</div>

