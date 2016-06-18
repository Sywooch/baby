<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $banners \app\modules\banner\models\Banner[]
 * @var $banner \app\modules\banner\models\Banner
 *
 */
use frontend\components\SmartFPM;
use yii\helpers\Html;
$classList = [
    'descr-purp',
    'descr-lilac',
    'descr-gray'
];
shuffle($classList);
$bannerCount = count($banners);
?>
<div class="slider-preloader">
    <span class="dot1"></span>
    <span class="dot2"></span><span
        class="dot3"></span>
</div>
<div class="h-slider inactive" data-rel="slider-1">
    <?php
    $i = 0;
    foreach ($banners as $banner) {
        if ($bannerCount == 1) {
            $class = $classList[array_rand($classList)];
        } else {
            $class = $classList[$i];
        }

        echo Html::beginTag(
            'div',
            [
                'class' => 'h-slider-item owl-lazy',
                'data-src' => SmartFPM::src($banner->image_id, 'banner', 'front')
            ]
        );
        $content = Html::tag($tagForBannerHeader, $banner->label);
        $content .= Html::tag('p', $banner->content);

        //Only 1 banner need to be with h1 header
        $tagForBannerHeader = 'span';

        if (!empty($banner->href)) {
            $content .= Html::tag('i', Yii::t('frontend', 'Show more'), ['class' => 'more']);

            echo Html::a($content, $banner->href, ['class' => 'descr '.$class]);
        } else {
            echo Html::tag('div', $content, ['class' => 'descr '.$class]);
        }

        echo Html::endTag('div');

        $i++;
        if ($i == 3) {
            $i = 0;
        }
    }
    ?>
</div>
<div class="pager-w">
    <div class="select"></div>
    <div class="pager" data-rel="slider-1"></div>
</div>
