<?php
/**
 * @var $pages \common\models\StaticPage[]
 */
?>
<?php foreach ($pages as $page): ?>
    <li><a href="<?= $page->getUrl() ?>" title="<?= $page->label ?>"><?= $page->label ?></a></li>
<?php endforeach; ?>

