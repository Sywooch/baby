<?php
/**
 * Author: Pavel Naumenko
 */

use backend\modules\store\models\StoreCategory;
use yii\helpers\Url;

\backend\assets\NestedSortableAsset::register($this);

$this->params['breadcrumbs'] = [
    [
        'label' => $model->getBreadCrumbRoot(),
        'url' => ['index']
    ],
    'Сортировка'
];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Сортировка категорий</h3>
    </div>
    <div class="panel-body">
        <?php
        echo \yii\widgets\ListView::widget(
            [
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'itemView' => '_item',
                'itemOptions' => [
                    'tag' => false,
                ],
                'options' => [
                    'tag' => 'ul',
                    'class' => 'list-group category-sortable',
                    'data-url' => Url::to(StoreCategory::getCategorySortUrl())
                ]
            ]
        ); ?>

    </div>
</div>

