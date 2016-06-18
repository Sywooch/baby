<?php
/**
 * Author: Pavel Naumenko
 */
?>
<div class="h-slider-w">
    <div class="slider-preloader"><span class="dot1"></span><span class="dot2"></span><span
            class="dot3"></span></div>
    <div class="nav-wrap nav-wrap-desc">
       <?= \frontend\modules\blog\widgets\blogMenu\Widget::widget(); ?>
    </div>
    <?= \app\modules\banner\widgets\bannerList\Widget::widget([
        'type' => \common\models\Banner::TYPE_BLOG,
    ]); ?>
</div>

<div class="blog-w clearfix">
    <div class="btn-submenu-open-w">
        <p class="btn-submenu-open"><?= Yii::t('frontend', 'Blog'); ?></p>
    </div>

    <?= \app\modules\banner\widgets\bannerList\Widget::widget([
        'type' => \common\models\Banner::TYPE_BLOG,
        'location' => \common\models\Banner::LOCATION_BOTTOM,
        'doNotUseDefaultBanner' => true
    ]); ?>

    <div class="blog-content-w">
        <?= \frontend\modules\blog\widgets\lastBlogBigArticle\Widget::widget(); ?>

        <div class="catalog-progress">
            <span><i></i></span>
        </div>
        <!--catalog-progress end-->

            <?php
            \yii\widgets\Pjax::begin();

            echo \frontend\components\CatalogListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}{empty_items}',
                'itemOptions' => ['class' => 'blog-item'],
                'itemView' => '_item',
                'emptyItemsClass' => 'blog-item tt-empty-empty',
                'options' => ['class' => 'blog-galery tt-effect-scalerotate tt-effect-delay clearfix']
            ]);

            echo \app\modules\store\widgets\pager\LinkPager::widget([
                'pagination'=>$dataProvider->pagination,
                'isBlogPage' => true
            ]);

            \yii\widgets\Pjax::end();
            ?>
    </div>

</div>
