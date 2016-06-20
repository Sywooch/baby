<?php
/**
 * @var $products StoreProduct[]
 */
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductCartPosition;
use metalguardian\fileProcessor\helpers\FPM;

?>
<div class="box">
    <div>
        <h1 class="title_module"><span><?= Yii::t('front', $title) ?></span></h1>
        <div class="box-content">
            <?php foreach ($products as $product): ?>
                <?php $url = StoreProduct::getProductUrl(['alias' => $product->alias]) ?>
                <div class="box-product">
                    <a class="image" href="<?= $url ?>" title="View more">
                        <?php $imageId = $product->mainImage->file_id;
                            if ($imageId) {
                                echo  FPM::image($imageId, 'product', 'mainPagePreview');
                            }
                        ?>
                        <?php if ($product->is_sale) { ?>
                            <span class="new"><?= \Yii::t('front', 'Sale') ?></span>
                        <?php } ?>
                    </a>
                    <h3 class="name">
                        <a href="<?= $url ?>" title=""><?= $product->label ?></a>
                    </h3>
                    <?= $this->render('//layouts/_wrap_price', ['product' => $product]) ?>
                    <p class="submit">
                        <a class="ajax-popup-link" href="<?= StoreProductCartPosition::getCartAddUrl(['id' => $product->id]) ?>">
                            <input type="button" value="<?= Yii::t('front', 'Add to Cart') ?>" class="button">
                        </a>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
