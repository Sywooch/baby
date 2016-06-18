<?php
/**
 * @var $banners \app\modules\banner\models\Banner[]
 */
use metalguardian\fileProcessor\helpers\FPM;

?>
<div id="slide-wrapper">
    <ul id="slider">
        <?php foreach ($banners as $banner): ?>
            <li>
                <div class="border_on_img"></div>
                <div class="content_slider">
                    <p><?= $banner->content ?></p>
                </div>
                <?= FPM::image($banner->image_id, 'banner', 'front', [
                    'alt' => $banner->content
                ]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
