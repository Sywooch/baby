<?php
/**
 * Author: Pavel Naumenko
 */

use app\modules\store\models\StoreCategory;
use frontend\modules\blog\models\BlogRubric;
use yii\helpers\Url;

$videoId = Yii::$app->config->get('about_us_vimeo_video_id');
?>
<div class="article-w">
    <h1><?= \Yii::t('frontend', 'About us'); ?></h1>
    <div class="article">
        <div class="post">
            <p><?= \Yii::t('aboutUs', 'about_us_text_before_image'); ?></p>
        </div>
    </div>

    <div class="slider-about-w">
        <div class="slider-about">
            <img class="owl-lazy" data-src="/img/about/desc/about_us_1.jpg" alt="">
            <img class="owl-lazy" data-src="/img/about/desc/about_us_2.jpg" alt="">
            <img class="owl-lazy" data-src="/img/about/desc/about_us_3.jpg" alt="">
        </div>
        <div class="pager-w">
            <div class="select"></div>
            <div class="pager" data-rel="slider-1"></div>
        </div>
    </div>

    <div class="article">
        <div class="post">
            <p><?= \Yii::t('aboutUs', 'about_us_text_before_video'); ?></p>
        </div>
    </div>

    <h1><?= \Yii::t('aboutUs', 'about_us_video_label'); ?></h1>

    <?php
    if ($videoId) {
        ?>
        <div class="video-wrapper video-vimeo">
            <div class="video" id="player" data-video='<?= $videoId; ?>'></div>
        </div>
        <?php
    }
    ?>

    <div class="bunners-w clearfix">
        <div class="col">
            <a class="btn-bunner-yellow bunners-item" href="<?= Url::to(StoreCategory::getCatalogRoute()); ?>">
                <span class="btn-yellow-wrapper-strong"><?= \Yii::t('aboutUs', 'catalog_block_label'); ?></span>
				<span><?= \Yii::t('aboutUs', 'catalog_block_desc'); ?></span>
            </a>

        </div>
        <div class="col">
            <a class="btn-bunner-img  bunners-item" href="<?= Url::to(BlogRubric::getBlogRoute()); ?>" style="background-image: url('/img/bg-banner.png')">
                <span class="btn-bunner-strong"><?= \Yii::t('aboutUs', 'blog_block_label'); ?></span>
                <span><?= \Yii::t('aboutUs', 'blog_block_desc'); ?></span>
            </a>

        </div>
    </div>
<?php /*
    <div class="bunner-info">
        <p class="bunner-info-tittle">Присоединяйся!</p>

        <p>И мы приглашаем тебя зарегистрироваться, что бы получать подарки и узнавать первой про новинки</p>
        <a href="#" class="btn-round btn-round__purp">
            <span>Зарегистрироваться!</span>
        </a>
    </div>
 */ ?>

</div>
