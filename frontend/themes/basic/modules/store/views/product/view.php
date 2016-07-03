<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\modules\store\models\StoreProduct
 */
use app\modules\store\models\StoreCategory;
use app\modules\store\models\StoreProductCartPosition;
use common\models\StoreProductAttribute;
use metalguardian\fileProcessor\helpers\FPM;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="breadcrumb">
    <?= $model->getCategoryBreadCrumbs(); ?> »
    <a href="#"><?= $model->label ?></a>
</div>
<h1><span class="h1-top"><?= $model->label ?></span></h1>
<div class="product-info">
    <div class="left">
        <div class="image">
            <?php if ($model->allImages) { ?>
                <?php $mainImageId = $model->allImages[0]->file_id ?>
                <?php $mainImageSrc = FPM::src($mainImageId, 'product', 'big') ?>
                <a href="<?= $mainImageSrc ?>" title="" class="cloud-zoom" id='zoom1' rel="adjustX: 0, adjustY:0">
                    <?= FPM::image($mainImageId, 'product', 'mainPreview') ?>
                </a>
                <div class="zoom">
                    <a id="zoomer" class="colorbox" href="<?= $mainImageSrc ?>">+ Zoom</a>
                </div>
            <?php } ?>
        </div>
        <?php if (count($model->allImages) > 1) { ?>
            <div class="image-additional">
                <div id="carousel-p">
                    <ul class="jcarousel-skin-tfc">
                        <?php foreach ($model->allImages as $image): ?>
                            <?php $imageSrc = FPM::src($image->file_id, 'product', 'big') ?>
                            <li>
                                <a href="<?= $imageSrc ?>" title="" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?= $imageSrc ?>' ">
                                    <?= FPM::image($image->file_id, 'product', 'smallPreview') ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php } ?>

    </div>
    <div class="right">
        <div class="description">
            <div class="price">
                <span id="line_s"></span>
                <p class="wrap_price">
                    <?php if ($model->getMinPrice()) { ?>
                        <span class="price-old"><?= $model->getOldPrice() ?></span>
                    <?php } ?>
                    <span class="price-new"><?= $model->getMinPriceWithCurrency() ?></span>
                </p>
            </div>
        </div>
        <div class="desc2">
            <span><?= \Yii::t('front', 'Product Code:') ?></span> <?= $model->sku ?><br>
            <span><?= \Yii::t('front', 'Availability:') ?></span> <?= \common\models\StoreProduct::getStatus($model->status) ?></div>
        <?php if ($model->getMinPrice()) { ?>
            <div class="cart">
                <?= \app\modules\store\widgets\productSizes\Widget::widget(['model' => $model]) ?>
                <br>
                <div><?= \Yii::t('front', 'Qty:') ?>
                    <input class="cart-quantity" type="text" name="quantity" size="2" value="1">
                    &nbsp;
                    <?php $url = StoreProductCartPosition::getCartAddUrl(['id' => $model->id, 'sizeId' => $model->productSizes[0]->id]) ?>
                    <a class="cart-add ajax-popup-link" href="<?= $url ?>" data-url="<?= $url ?>">
                        <input type="button" value="<?= \Yii::t('front', 'Add to Cart') ?>" id="button-cart" class="button">
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div id="tabs" class="htabs"><a href="#tab-description"><?= \Yii::t('front', 'Description') ?></a></div>
<div id="tab-description" class="tab-content">
    <div class="cpt_product_description ">
        <div>
            <?= $model->content ?>
        </div>
    </div>
</div>
