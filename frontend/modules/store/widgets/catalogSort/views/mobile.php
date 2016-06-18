<?php
/**
 * Author: Pavel Naumenko
 *
 * @var array $sort
 * @var array $filters
 * @var string $default
 * @var string $selectedFilter
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="catalog-sort clearfix">
    <div class="sort-all">
        <p>
            <?php
            $filterCount = count($filters);

            if ($filterCount) {
                echo Html::a(
                    Yii::t('frontend', 'All'),
                    Url::current(['filter' => null]),
                    ['class' => $selectedFilter ? '' : 'no-active']
                );
                echo Html::tag('span', Yii::t('frontend', 'more'), ['class' => 'catalog-sort-strong']);
                echo Html::tag('i', null, ['class' => 'btn-sort']);
            }
            ?>
        </p>

        <?php
        if ($filterCount) {
            echo Html::beginTag('div', ['class' => 'select']);
            echo Html::beginTag('ul');
            foreach ($filters as $filter) {
                echo Html::tag(
                    'li',
                    Html::a($filter->label, Url::current(['filter' => $filter->id]))
                );
            }
            echo Html::endTag('ul');
            echo Html::endTag('div');
        }
        ?>
    </div>
</div>
