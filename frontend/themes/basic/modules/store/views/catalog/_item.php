<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model StoreProduct
 */
use app\modules\store\models\StoreProduct;
use metalguardian\fileProcessor\helpers\FPM;
use yii\helpers\Html;

?>
<a href="<?= StoreProduct::getProductUrl(['alias' => $model->alias]) ?>" data-pjax="0">
    <?php
    if ($model->is_new) {
        echo Html::tag('i', 'new', ['class' => 'new']);
    }

    echo FPM::image(
        $model->mainImage->file_id,
        'product',
        'frontPreview',
        [
            'alt' => \frontend\components\SeoHelper::getCatalogImageAlt($model->label),
            'class' => $class
        ]
    );

    ?>
    <span class="hover">
        <em class="em-big"><?= $model->getShortLabel(); ?></em>
        <div class="em-small">Тип товара: <?= $model->getProductType(); ?></div>
        <div class="em-small">Назначение: <?= $model->getProductCategory(); ?></div>
        <div class="em-small">Вид товара: <?= $model->getProductSubCategory(); ?></div>
        <div class="em-small">Страна производитель: <?= $model->getProductCountry(); ?></div>
        <span class="catalog-strong"><?= $model->getAvailability(); ?>
            <br />Купить
        </span>
    </span>
</a>