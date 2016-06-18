<?php
/**
 * Author: Pavel Naumenko
 *
 * @var Sales[] $sales
 * @var Sales $sale
 */
use frontend\modules\sales\models\Sales;
use yii\helpers\Html;

?>
<div class="sale-w ">
    <?php
    echo Html::beginTag('div', ['class' => 'sale-row clearfix']);
    foreach ($sales as $i => $sale) {
        if ($i && ($i % 2 === 0)) {
            echo Html::endTag('div');
            echo Html::beginTag('div', ['class' => 'sale-row clearfix']);
        }

        echo Html::beginTag('div', ['class' => 'sale-row-item']);

        $saleContent = '';
        $class = $sale->type == \common\models\Sales::TYPE_WHITE
            ? 'btn-bunner-yellow btn-bunner-white'
            : 'btn-bunner-yellow';

        if (!empty($sale->label)) {
            $saleContent .= Html::tag('span', $sale->label, ['class' => 'btn-yellow-wrapper-strong']);
        }

        $saleContent .= Html::tag('span', $sale->description);

        if (!empty($sale->content)) {
            $saleContent .= Html::tag('i', Yii::t('frontend', 'get more'), ['class' => 'more']);
            echo Html::a($saleContent, Sales::getViewUrl(['alias' => $sale->alias]), ['class' => $class]);
        } else {
            echo Html::tag('div', $saleContent, ['class' => $class]);
        }

        echo Html::endTag('div');
    }
    echo Html::endTag('div');
    ?>
</div>
