<?php
/**
 * Author: Pavel Naumenko
 */
use yii\helpers\Html;

?>
<div class="delivery-mob-w">
    <?php
    if (isset($data['delivery'])) {
        $output = Html::beginTag('div', ['class' => 'delivery-mob-item']);
        $output .= Html::tag('p', Yii::t('payAndDelivery', 'delivery'), ['class' => 'delivery-mob-title']);
        $output .= Html::beginTag('ul');

        /**
         * @var \frontend\modules\common\models\PayAndDelivery $delivery
         */
        foreach ($data['delivery'] as $delivery) {
            $output .= Html::beginTag('li');

            $output .= Html::tag('p', $delivery->name);
            $output .= Html::tag('p', $delivery->price);
            $output .= Html::tag('p', $delivery->getKievAndRegionsStatusForMob());

            $output .= Html::endTag('li');
        }

        $output .= Html::endTag('ul');
        $output .= Html::endTag('div');

        echo $output;
    }

    if (isset($data['pay'])) {
        $output = Html::beginTag('div', ['class' => 'delivery-mob-item']);
        $output .= Html::tag('p', Yii::t('payAndDelivery', 'pay'), ['class' => 'delivery-mob-title']);
        $output .= Html::beginTag('ul');

        /**
         * @var \frontend\modules\common\models\PayAndDelivery $delivery
         */
        foreach ($data['pay'] as $pay) {
            $output .= Html::beginTag('li');

            $output .= Html::tag('p', $pay->name);
            $output .= Html::tag('p', $pay->price);
            $output .= Html::tag('p', $pay->getKievAndRegionsStatusForMob());

            $output .= Html::endTag('li');
        }

        $output .= Html::endTag('ul');
        $output .= Html::endTag('div');

        echo $output;
    }
    ?>
</div>
