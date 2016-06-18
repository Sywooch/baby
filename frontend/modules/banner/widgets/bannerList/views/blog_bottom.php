<?php
/**
 * Author: Pavel Naumenko
 */
use metalguardian\fileProcessor\helpers\FPM;

?>
<div class="banner-w">
    <?php /*
    <div class="bunner-item">
        <a href="#" class="top10">
            <span class="big-text">топ-10</span>
            <span>ежедневников</span>
        </a>
    </div>
    */ ?>
    <?php foreach ($banners as $banner): ?>
        <div class="bunner-item">
            <a href="<?= empty($banner->href) ? '#' : $banner->href; ?>">
                <img src="<?= FPM::src($banner->image_id, 'banner', 'blogBottom') ?>" alt=""/>
            </a>
        </div>
    <?php endforeach; ?>
</div>
