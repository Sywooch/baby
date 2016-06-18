<?php
/**
 * Author: Pavel Naumenko
 *
 * @var \app\modules\store\forms\OrderForm $model
 */

use frontend\models\DummyModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$cart = Yii::$app->cart;
$isEmpty = $cart->getIsEmpty();
if (!$isEmpty) :
    if (Yii::$app->user->isGuest): ?>
        <p class="main-title"><?= Yii::t('frontend', 'fill_form_label'); ?>
            <a class="btn-round" href="<?= \frontend\models\DummyModel::getLoginLink(); ?>">
                <span><?= Yii::t('frontend', 'login') ?></span>
            </a> (<?= Yii::t('frontend', 'cart_form_will_fill_automatically') ?>)
        </p>
<?php endif;


    $form = ActiveForm::begin([
            'id' => 'order-form',
            'enableAjaxValidation' => true,
            'errorCssClass' => 'error',
            'fieldConfig' => [
                'errorOptions' => [
                    'tag' => 'div',
                    'class' => 'error-text'
                ]
            ]
    ]) ?>
    <div class="ordering-i clearfix">
    <div class="col-first">
        <div class="col">
            <div class="input-row input-row__yellow">
                <div class="input-item">
                    <?= $form->field($model, 'name') ?>
                </div>
            </div>
            <div class="input-row input-row__yellow input-row__padding">
                <div class="row-i clearfix">
                    <div class="input-item cod">
                        <span class="plus-cod">+</span>
                        <input name="code" id="code" type="text" value="380" readonly>
                    </div>

                    <div class="input-item number">
                        <?= $form->field($model, 'phone', ['template' => "{input}\n{error}"]) ?>
                    </div>
                </div>
            </div>
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'email') ?>
                </div>
            </div>

            <div class="payment">
                    <?=
                    $form->field($model, 'paymentType', ['template' => "{input}\n{error}"])->radioList(
                        [
                            \common\models\StoreOrder::PAYMENT_TYPE_CASH => Yii::t('frontend', 'Order_form_cash'),
                            \common\models\StoreOrder::PAYMENT_TYPE_CASH_PICKUP => Yii::t('frontend', 'Order_form_cash_on_delivery'),
                            \common\models\StoreOrder::PAYMENT_TYPE_VISA => Yii::t('frontend', 'Order_form_visa_payment')
                        ],
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                    return Html::tag(
                                        'div',
                                        Html::input('radio', $name, $value, ['checked' => $checked, 'id' => 'radio_payment_'.$index, 'class' => 'radioclass']).
                                        Html::label($label, 'radio_payment_'.$index),
                                        ['class' => 'radio-row clearfix']
                                    );
                                },
                        ]
                    ); ?>
                <?php /**
                <div class="radio-row clearfix">

                    <input type="radio" name='payment' id='radio-p3' class="radioclass">
                    <label for="radio-p3">
                        <i class="i-visa-gray"></i>
                        <i class="i-mc-gray"></i>
                    </label>
                </div>
                */ ?>
            </div>
        </div>
        <div class="col">
            <div class="delivery">
                    <?=
                    $form->field(
                        $model,
                        'deliveryType',
                        [
                            'template' => "{input}\n{error}",
                        ]
                    )->radioList(
                            [
                                1 => Yii::t('frontend', 'Order_form_courier'),
                                2 => Yii::t('frontend', '"Order_form_newPoshta"'),
                                3 => Yii::t('frontend', 'Order_form_pickup'),
                            ],
                            [
                                'unselect' => 1,
                                'item' => function ($index, $label, $name, $checked, $value) {
                                        $preText = '';
                                        $deliverySum = 0;

                                        if ($index == 0) {
                                            $classSuffix = 'courier';
                                            $preText = Html::tag('p', '35 грн');
                                            $deliverySum = \app\modules\store\models\StoreProduct::getCourierDeliveryPrice();
                                        } elseif ($index == 1) {
                                            $classSuffix = 'newpost';
                                        } else {
                                            $classSuffix = 'pickup';
                                            $preText = Html::tag('p', Yii::t('frontend', 'Order_form_free'));
                                        }

                                        return
                                            Html::tag(
                                                'div',
                                                $preText . Html::input(
                                                    'radio',
                                                    $name,
                                                    $value,
                                                    [
                                                        'checked' => $checked,
                                                        'id' => 'radio_delivery_' . $index,
                                                        'class' => 'radioclass ' . $classSuffix,
                                                        'data-sum' => $deliverySum
                                                    ]
                                                ) .
                                                Html::label($label, 'radio_delivery_' . $index),
                                                ['class' => 'radio-row clearfix']
                                            );
                                    },
                            ]
                        );

                    echo Html::tag(
                        'p',
                        Yii::t('frontend', 'Order_form_address'),
                        ['class' => 'address']
                    );
                    ?>

            </div>

            <div class="delivery-add">
                <div class="delivery-item courier">
                    <div class="input-row">
                        <div class="input-item">
                            <?= $form->field($model, 'address') ?>
                        </div>
                    </div>

                    <div class="input-row courier">
                        <?php /*
                        <div class="input-col">
                            <div class="input-item">
                                <?= $form->field($model, 'address') ?>
                            </div>
                        </div>
                        <div class="input-col">
                           <div class="input-item">
                               <?= $form->field($model, 'apartment') ?>
                           </div>
                        </div>
                    */ ?>
                    </div>
                    <div class="input-row">
                        <?=
                        $form->field($model, 'deliveryTime')->dropDownList(
                            [
                                Yii::t('frontend', 'Order_form_delivery_any_time'),
                                Yii::t('frontend', 'Order_form_delivery_morning'),
                                Yii::t('frontend', 'Order_form_delivery_dinner'),
                                Yii::t('frontend', 'Order_form_delivery_evening'),
                            ],
                            [
                                'class' => 'select-time'
                            ]
                        ); ?>
                    </div>
                </div>
                <div class="delivery-item newpost">
                    <div class="input-row">
                        <div class="input-item">
                            <?= $form->field($model, 'novaPoshtaStorage')->textarea(); ?>
                        </div>
                    </div>
                </div>
                <div class="delivery-item pickup delivery-item__show">
                    <p><?= Yii::t('frontend', 'Order_form_delivery_text'); ?></p>
                    <p><?= Yii::t('frontend', 'Order_form_details_in'); ?>
                        <a target="_blank" class=" btn-more-info" href="<?= Url::to(DummyModel::getDeliveryRoute()) ?>">
                            <?= Yii::t('frontend', 'Order_form_delivery_details'); ?>
                        </a>
                    </p>
                </div>
            </div>

        </div>
        <div class="discount-w">
            <a class="btn-discount btn-more-info" href="#"><?= Yii::t('frontend', 'Order_form_enter_comment_or_discount'); ?></a>
            <div class="discount-i">
                <p><?= Yii::t('frontend', 'Order_form_enter_comment_or_discount'); ?></p>
                <div class="input-row clearfix">
                    <div class="input-col">
                        <div class="input-item">
                            <?= $form->field($model, 'comment'); ?>
                        </div>
                    </div>
                    <div class="input-col">
                        <div class="input-item">
                            <?= $form->field($model, 'discountCard'); ?>
                        </div>
                    </div>
                    <div class="input-col">
                        <div class="input-item">
                            <?php // $form->field($model, 'promoCode'); ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-last">
        <p class="title">
            <?= Yii::t('frontend', 'Order_form_confirm_order'); ?>
        </p>
        <table class="tb-confirm">
            <tr>
                <td><?= Yii::t('frontend', 'Order_form_total_sum'); ?></td>
                <td class="total-sum-confirm"><b><?= $cart->getCost(); ?></b> грн</td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'Order_form_delivery'); ?></td>
                <td class="delivery-price"><b>0</b> грн</td>
            </tr>
        </table>
        <p class="result-sum"><?= Yii::t('frontend', 'Order_form_total'); ?>
            <span><span class="order-form-strong">0</span> грн</span>
        </p>
        <a class="btn-square btn-square-yellow form-submit" href="#">
            <span><?= Yii::t('frontend', 'Order_form_submit_order'); ?></span>
        </a>
    </div>
    </div>
    <?php
    echo Html::hiddenInput('freeDeliveryPrice', \app\modules\store\models\StoreProduct::getFreeDeliveryPrice());
    ActiveForm::end();
endif;

