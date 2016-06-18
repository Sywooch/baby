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
    <div class="sort-date">
        <p><?= Yii::t('frontend', 'Sort by') ?>
            <span> <?= $default; ?></span>
            <i class="btn-sort"></i>
        </p>

        <div class="select">
            <ul>
                <?php
                foreach ($sort as $s) {
                    echo Html::beginTag('li');
                    echo Html::a($s['label'], $s['url']);
                    echo Html::endTag('li');
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="sort-all">
        <p>
            <?php
            $filterCount = count($filters);

            echo $filterCount
                ? Html::a(
                    Yii::t('frontend', 'All'),
                    Url::current(['filter' => null]),
                    ['class' => $selectedFilter ? '' : 'no-active']
                )
                : null;

            if (isset($filters[0])) {
                echo Html::a(
                    Html::tag('span', $filters[0]->label),
                    Url::current(['filter' => $filters[0]->id]),
                    ['class' => $selectedFilter == $filters[0]->id ? 'no-active' : '']
                );
            }
            if (isset($filters[1])) {
                echo Html::a(
                    Html::tag('span', $filters[1]->label),
                    Url::current(['filter' => $filters[1]->id]),
                    ['class' => $selectedFilter == $filters[1]->id ? 'no-active' : '']
                );
            }

            if ($filterCount > 2) {
                echo Html::tag('span', Yii::t('frontend', 'more'), ['class' => 'catalog-sort-strong']);
                echo Html::tag('i', null, ['class' => 'btn-sort']);
            }

            ?>
        </p>

        <?php
        if ($filterCount > 2) {
            echo Html::beginTag('div', ['class' => 'select']);
            echo Html::beginTag('ul');
            for ($i = 2; $i < $filterCount; $i++) {
                echo Html::tag(
                    'li',
                    Html::a($filters[$i]->label, Url::current(['filter' => $filters[$i]->id]))
                );
            }
            echo Html::endTag('ul');
            echo Html::endTag('div');
        }
        ?>
    </div>
</div>
