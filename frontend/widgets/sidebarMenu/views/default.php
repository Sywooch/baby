<?php
/**
 * @var $categories StoreCategory[]
 * @var $child StoreCategory
 */
use app\modules\store\models\StoreCategory;
?>
<div class="box">
    <div class="box-heading"><?= \Yii::t('front', 'Categories') ?></div>
    <div class="box-content">
        <ul class="box-category">
            <?php $count = count($categories) - 1 ?>
            <?php foreach ($categories as $key => $category): ?>
                <li class="<?= $count == $key ? 'last-item' : '' ?>">
                    <a href="<?= $category->getCatalogUrl() ?>" class="active"><?= $category->label ?></a>
                    <?php $children = $category->getChildren() ?>
                    <?php if ($children) { ?>
                        <ul>
                            <?php $count = count($children) - 1 ?>
                            <?php foreach ($children as $childKey => $child): ?>
                                <li class="<?= $count == $childKey ? 'last-item' : '' ?>">
                                    <a href="<?= $child->getCatalogUrl() ?>"><?= $child->label ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php } ?>
                    
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
