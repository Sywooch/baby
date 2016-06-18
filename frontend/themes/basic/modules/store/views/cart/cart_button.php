<?php
/**
 * Author: Pavel Naumenko
 */

$positionCount = count(Yii::$app->cart->getPositions());
?>
<?php if ($positionCount) { ?>
    <a href="<?= \app\modules\store\models\StoreProductCartPosition::getSmallCartUrl(); ?>" class="btn-buscket">
        <span><i class="icon-buccket"></i><?=
            Yii::t(
                'frontend',
                '<b>{count, plural, =1{#</b>product</span>} =2{#</b> product</span>} =3{#</b> product</span>} =4{#</b> product</span>} other{#</b> products</span>}}',
                [
                    'count' => $positionCount
                ]
            ) ?></span>
    </a>
<?php
}
