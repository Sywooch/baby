<?php
/**
 * Author: Pavel Naumenko
 */
use app\modules\store\models\StoreCategory;
use yii\helpers\Url;

$cart = Yii::$app->cart;
$isEmpty = $cart->getIsEmpty();
?>
<div class="breadcrumbs-w">
    <div class="back-catalog-w">
        <a class="btn-back-catalog" href="<?= Url::to(StoreCategory::getCatalogRoute()); ?>"><?= Yii::t('frontend', 'Continue shopping') ?> <i class="y-circle"></i></a>
    </div>
    <div class="breadcrumb hide">
        <a href="<?= Url::toRoute('/'); ?>"><?= Yii::t('frontend', 'Store Chicardi'); ?> </a>
        /
        <span><?= Yii::t('frontend', 'cart'); ?></span>
    </div>
</div>
<?php if (Yii::$app->session->hasFlash('cart')) {
    echo \yii\helpers\Html::tag('div', Yii::$app->session->getFlash('cart'), ['class' => 'flash']);
} ?>
<div class="buscket-w clearfix">
    <div class="main-cart-header">
        <?= $this->context->renderPartial('_main_cart_header'); ?>
    </div>
    <?= $this->context->renderPartial('_cart_positions'); ?>
    <div class="total-sum">
        <p><?= Yii::t('frontend', 'total sum'); ?></p>

        <p><b><?= $cart->getCost() ?></b>грн</p>
    </div>
</div>

<div class="ordering-w">
<?= $this->context->renderPartial('_order_form', ['model' => $form]); ?>
</div>
