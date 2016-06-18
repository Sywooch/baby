<?php
/**
 * @var $categories StoreCategory[]
 * @var $child StoreCategory
 */
use app\modules\store\models\StoreCategory;
?>
<?php foreach ($categories as $category): ?>
<h3><?= $category->label ?></h3>
<div class="mobile-footer-nav" style="display: none;">
    <ul>
        <?php foreach ($category->getChildren() as $childKey => $child): ?>
            <li>
                <a href="<?= $child->getCatalogUrl() ?>"><?= $child->label ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endforeach; ?>
