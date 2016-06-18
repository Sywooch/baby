<?php
/**
 * 
 */
use app\modules\store\models\StoreProduct;

?>
<div id="column-left">
    <?= \frontend\widgets\sidebarMenu\Widget::widget() ?>
    <?= \app\modules\store\widgets\productsOnMainPage\Widget::widget(['type' => StoreProduct::WIDGET_POPULAR]) ?>
    <div class="clear"></div>
    <?= \app\modules\store\widgets\productsOnMainPage\Widget::widget(['type' => StoreProduct::WIDGET_SALE]) ?>
    <div class="clear"></div>
    <?= \app\modules\store\widgets\productsOnMainPage\Widget::widget(['type' => StoreProduct::WIDGET_LATEST]) ?>
    <div class="clear"></div>
</div>
