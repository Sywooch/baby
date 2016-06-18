<?php
/**
 * @var $pageSize integer
 * @var $variants []
 */
use yii\helpers\Url;

?>
<div class="limit">
    <label><?= \Yii::t('front', 'Show:') ?></label>
    <select class="selectBox limit-items">
        <?php foreach ($variants as $item): ?>
            <option value="<?= Url::current(['show' => $item]) ?>" <?= $item == $pageSize ? 'selected="selected"' : '' ?>>
                <?= $item ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
