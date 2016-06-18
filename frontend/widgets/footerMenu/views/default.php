<?php
/**
 * @var $categories StoreCategory[]
 * @var $child StoreCategory
 */
use app\modules\store\models\StoreCategory;
?>
<?php foreach ($categories as $key => $category): ?>
<div class="footer_bottom_item">
    <h3 class="bottom_item_<?= $key + 2 ?> down"><a><?= $category->label ?></a></h3>
    <ul class="menu_footer_item text_item">
        <?php foreach ($category->getChildren() as $childKey => $child): ?>
            <li>
                <a href="<?= $child->getCatalogUrl() ?>"><?= $child->label ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endforeach; ?>
