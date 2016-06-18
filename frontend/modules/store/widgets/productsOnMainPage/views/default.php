<?php
/**
 * @var $products StoreProduct[]
 */
use app\modules\store\models\StoreProduct;

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
                                echo  \metalguardian\fileProcessor\helpers\FPM::image($imageId, 'product', 'mainPagePreview');
                            }
                        ?>
                    </a>
                    <h3 class="name">
                        <a href="<?= $url ?>" title=""><?= $product->label ?></a>
                    </h3>
                    <p class="wrap_price">
                        <?php if ($product->old_price) { ?>
                            <span class="price-old"><?= $product->getOldPrice() ?></span>
                            <span class="price-new"><?= $product->getPrice() ?></span>
                        <?php } else { ?>
                            <span class="price"><?= $product->getPrice() ?></span>
                        <?php } ?>
                    </p>
                    <p class="submit">
                        <input type="button" value="<?= Yii::t('front', 'Add to Cart') ?>" class="button">
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
