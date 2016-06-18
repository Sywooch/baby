<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $banner \app\modules\banner\models\Banner
 */
use metalguardian\fileProcessor\helpers\FPM;
use yii\helpers\Html;

$banner = $banners[0];
?>

<div class="sale sale__left clearfix">
    <div class="club-w clearfix">
        <img class="bg-club bg-book" src="" data-big="<?= FPM::src($banner->image_id, 'banner', 'categoryBottom') ?>" alt=""/>

        <?php
        $content = Html::tag('span', Html::tag('span', $banner->label, ['class' => 'club-title-strong']).$banner->small_label, ['class' => 'club-title']);
        if (!empty($banner->content)) {
            $content .= Html::tag('span', $banner->content, ['class' => 'club-text']);
        }

        if (!empty($banner->href)) {
            $content .= Html::tag('span', Html::tag('span', Yii::t('frontend', 'see'), ['class' => 'club-want-strong']), ['class' => 'want']);

            echo Html::a($content, $banner->href, ['class' => 'club']);
        } else {
            echo Html::tag('div', $content, ['class' => 'club']);
        }

        ?>
    </div>
</div>
