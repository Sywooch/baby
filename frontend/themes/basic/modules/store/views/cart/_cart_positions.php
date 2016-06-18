<?php
/**
 * Author: Pavel Naumenko
 */
use app\modules\store\models\StoreProduct;
use app\modules\store\models\StoreProductCartPosition;
use metalguardian\fileProcessor\helpers\FPM;
use yii\helpers\Html;

$cart = Yii::$app->cart;
$isEmpty = $cart->getIsEmpty();
?>
<div class="purchase">
    <?php
    $positions = $cart->getPositions();

    $i = 1;
    foreach ($positions as $position) {
        /**
         * @var StoreProductCartPosition $position
         */
        $quantity = $position->getQuantity();
        $decreaseButtonClass = $quantity > 1 ? 'btn-decrease active' : 'btn-decrease' ;

        echo Html::beginTag('div', ['data-id' => $i, 'class' => 'purchase-item']);
        echo Html::beginTag('div', ['class' => 'btn-delete-w']);
        echo Html::a('', $position->getRemoveUrl(true), ['class' => 'btn-delete ajax-link']);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'img-w']);
        echo ($position instanceof StoreProductCartPosition)
            ? FPM::image($position->mainImage->file_id, 'product', 'smallPreview', ['alt' => $position->label])
            : Html::tag('span', $position->getPrice(), ['class' => $position->getColorClass()]);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'descr']);
        echo ($position instanceof StoreProductCartPosition)
            ? Html::tag('p', Html::a($position->getLabel(), StoreProduct::getProductUrl(['alias' => $position->alias])), ['class' => 'title'])
            : Html::tag('p', $position->getLabel(), ['class' => 'title']);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'price-one']);
        echo Html::tag('p', Html::tag('span', $position->getPrice()). ' грн', ['class' => 'price']);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'price-w']);
        echo Html::tag('p', Html::tag('span', $position->getCost()). ' грн', ['class' => 'price', 'data-price' => $position->getPrice()]);
        echo Html::tag(
            'p',
            Html::tag(
                'span',
                Html::tag('b', $quantity) . ' шт'
            )
            .
            Html::tag('i', null, [
                    'class' => $decreaseButtonClass,
                    'data-url' => $position->getRemoveUrl(),
                ]) .
            Html::tag('i', null, [
                    'class' => 'btn-increase active',
                    'data-url' => $position->getAddUrl(),
                ]),
            ['class' => 'count']
        );
        echo Html::endTag('div');
        echo Html::endTag('div');

        $i++;
    }
    ?>

</div>
