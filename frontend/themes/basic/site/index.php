<?php
/* @var $this yii\web\View */
use app\modules\store\models\StoreProduct;

?>
<?= \frontend\widgets\slider\Widget::widget() ?>
<div class="banner">
    <div><a href="specials.html"><img src="image/small-banner-green-225x161.png" alt="Lorem ipsum dolor sit amet, consectetur adipiscing elit" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"></a> </div>
</div>
<div class="banner">
    <div><img src="image/small-banner-blue-225x161.png" alt="Lorem ipsum dolor sit amet, consectetur adipiscing elit" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"> </div>
</div>
<?= \app\modules\store\widgets\productsOnMainPage\Widget::widget(['type' => StoreProduct::WIDGET_LATEST]) ?>
<div class="clear"></div>
<?= \app\modules\store\widgets\productsOnMainPage\Widget::widget(['type' => StoreProduct::WIDGET_SALE]) ?>
<div class="clear"></div>
<?= \app\modules\store\widgets\productsOnMainPage\Widget::widget(['type' => StoreProduct::WIDGET_POPULAR]) ?>
<div class="clear"></div>
