<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model \backend\modules\store\models\StoreCategory
 */
$childs = $model->children()->all();
?>
<li class="list-group-item sort-item" id="item_<?= $model->id; ?>">
    <div>
        <span class="badge"><i class="glyphicon glyphicon-move"></i></span>
        <?= $model->label; ?>
    </div>

    <?php if (!empty($childs)) { ?>
            <ul>
                <?php foreach ($childs as $child) {
                    ?>
                    <li class="list-group-item sort-item no-place mjs-nestedSortable-no-nesting" id="item_<?= $child->id; ?>">
                        <div>
                            <span class="badge"><i class="glyphicon glyphicon-move"></i></span>
                            <?= $child->label; ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
    <?php } ?>
</li>


