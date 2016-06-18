<?php
/**
 * Author: Pavel Naumenko
 *
 * @var array $data
 */
use yii\helpers\Html;

?>
<div class="delivery-table-w hide">
    <div class="delivery-table">
        <div class="delivery-thead clearfix">
            <div class="col col__big payment">
                <p><?= Yii::t('payAndDelivery', 'Pay_and_delivery_types') ?></p>
            </div>
            <div class="col col__big price">
                <p><?= Yii::t('payAndDelivery', 'Price') ?></p>
            </div>
            <div class="col city-kiev">
                <p><?= Yii::t('payAndDelivery', 'for_Kyiv') ?></p>
            </div>
            <div class="col region">
                <p><?= Yii::t('payAndDelivery', 'for_regions') ?></p>
            </div>
        </div>
        <div class="delivery-tbody">
            <?php
            if (isset($data['delivery'])) {
                $output = Html::beginTag('div', ['class' => 'delivery-tbody-item']);
                $output .= Html::tag('p', Yii::t('payAndDelivery', 'delivery'), ['class' => 'title-vertical']);

                /**
                 * @var \frontend\modules\common\models\PayAndDelivery $delivery
                 */
                foreach ($data['delivery'] as $delivery) {
                    $output .= Html::beginTag('div', ['class' => 'delivery-tbody-row clearfix']);

                    $output .= Html::beginTag('div', ['class' => 'col col__big payment']);
                    $output .= Html::tag('p', $delivery->name);
                    $output .= Html::endTag('div');

                    $output .= Html::beginTag('div', ['class' => 'col col__big price']);
                    $output .= Html::tag('p', $delivery->price);
                    $output .= Html::endTag('div');

                    $output .= Html::beginTag('div', ['class' => 'col city-kiev']);
                    $output .= Html::tag('p', $delivery->for_kiev ? '<i class="icon-heart-y"></i>' : '&mdash;');
                    $output .= Html::endTag('div');

                    $output .= Html::beginTag('div', ['class' => 'col region']);
                    $output .= Html::tag('p', $delivery->for_regions ? '<i class="icon-heart-y"></i>' : '&mdash;');
                    $output .= Html::endTag('div');

                    $output .= Html::endTag('div');
                }

                $output .= Html::endTag('div');

                echo $output;
            }

            if (isset($data['pay'])) {
                $output = Html::beginTag('div', ['class' => 'delivery-tbody-item']);
                $output .= Html::tag('p', Yii::t('payAndDelivery', 'pay'), ['class' => 'title-vertical']);

                /**
                 * @var \frontend\modules\common\models\PayAndDelivery $pay
                 */
                foreach ($data['pay'] as $pay) {
                    $output .= Html::beginTag('div', ['class' => 'delivery-tbody-row clearfix']);

                    $output .= Html::beginTag('div', ['class' => 'col col__big payment']);
                    $output .= Html::tag('p', $pay->name);
                    $output .= Html::endTag('div');

                    $output .= Html::beginTag('div', ['class' => 'col col__big price']);
                    $output .= Html::tag('p', $pay->price);
                    $output .= Html::endTag('div');

                    $output .= Html::beginTag('div', ['class' => 'col city-kiev']);
                    $output .= Html::tag('p', $pay->for_kiev ? '<i class="icon-heart-p"></i>' : '&mdash;');
                    $output .= Html::endTag('div');

                    $output .= Html::beginTag('div', ['class' => 'col region']);
                    $output .= Html::tag('p', $pay->for_regions ? '<i class="icon-heart-p"></i>' : '&mdash;');
                    $output .= Html::endTag('div');

                    $output .= Html::endTag('div');
                }

                $output .= Html::endTag('div');

                echo $output;
            }
            ?>
        </div>

    </div>
</div>
