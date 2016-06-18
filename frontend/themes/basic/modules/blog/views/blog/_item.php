<?php
/**
 * Author: Pavel Naumenko
 */
use metalguardian\fileProcessor\helpers\FPM;

?>
<a data-pjax="0" href="<?= \frontend\modules\blog\models\BlogArticle::getViewUrl(['alias' => $model->alias]); ?>">
    <span class="g-zoom">
        <?= FPM::image($model->file_id, 'blog', 'preview', ['alt' => $model->label, 'class' => 'blog-preview']); ?>
    </span>
    <span class="g-item-title">
        <?= $model->label; ?>
        <i class="y-circle"></i>
    </span>
</a>
