<?php
/**
 * @var $sort string
 */
use yii\helpers\Url;

?>
<div class="sort">
    <label><?= \Yii::t('front', 'Sort By:') ?></label>
    <select class="selectBox sort-items">
        <option value="<?= Url::current(['sort' => 'default']) ?>" <?= !$sort ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Default') ?></option>
        <option value="<?= Url::current(['sort' => 'label']) ?>" <?= $sort == 'label' ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Name (A - Z)') ?></option>
        <option value="<?= Url::current(['sort' => '-label']) ?>" <?= $sort == '-label' ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Name (Z - A)') ?></option>
        <option value="<?= Url::current(['sort' => 'price']) ?>" <?= $sort == 'price' ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Price (Low &gt; High)') ?></option>
        <option value="<?= Url::current(['sort' => '-price']) ?>" <?= $sort == '-price' ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Price (High &gt; Low)') ?></option>
        <option value="<?= Url::current(['sort' => 'is_popular']) ?>" <?= $sort == 'is_popular' ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Rating (Highest)') ?></option>
        <option value="<?= Url::current(['sort' => '-is_popular']) ?>" <?= $sort == '-is_popular' ? 'selected="selected"' : '' ?>><?= \Yii::t('front', 'Rating (Lowest)') ?></option>
    </select>
</div>
