<?php
/**
 * @var $model StoreProduct
 */
use app\modules\store\models\StoreProduct;
use metalguardian\fileProcessor\helpers\FPM;

$url = $model->getProductUrl(); ?>
<div class="box-product">
    <?php if ($model->is_sale) { ?>
        <span class="new"><?= \Yii::t('front', 'Sale') ?></span>
    <?php } ?>
    <a class="image" href="<?= $url ?>" title="<?= \Yii::t('front', 'View more') ?>">
        <?php $imageId = $model->mainImage->file_id;
        if ($imageId) {
            echo  FPM::image($imageId, 'product', 'mainPreview');
        }
        ?>
        <?php if ($model->is_sale) { ?>
            <span class="new2"><?= \Yii::t('front', 'Sale') ?></span>
        <?php } ?>
    </a>
    <div class="list_grid_right">
        <h3 class="name"><a href="<?= $url ?>" title=""><?= $model->label ?></a></h3>
        <?= $this->render('//layouts/_wrap_price', ['product' => $model]) ?>
        <p class="description"><?= $model->announce ?></p>
        <p class="submit">
            <input type="button" value="<?= \Yii::t('front', 'Add to Cart') ?>" class="button">
        </p>
    </div>
</div>