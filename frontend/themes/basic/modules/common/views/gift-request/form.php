<?php
/**
 * Author: Pavel Naumenko
 *
 * @var \frontend\modules\common\forms\GiftRequestForm $model
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php if (Yii::$app->session->hasFlash('gift')) {
    echo \yii\helpers\Html::tag('div', Yii::$app->session->getFlash('gift'), ['class' => 'flash']);
} ?>
<div class="article-w">
    <h1><?= Yii::t('frontend', 'get_gift_page_label'); ?></h1>

    <div class="article">
        <div class="post">
            <p><?= Yii::t('frontend', 'get_gift_page_announce'); ?></p>
        </div>
        <div class="img-big">
            <img data-big="/img/article/desc/img-pres1.png" data-small="/img/article/mob/img-pres1.png" alt="img1">
        </div>
        <div class="post">
            <p><?= Yii::t('frontend', 'get_gift_page_text_before_form'); ?></p>
        </div>
    </div>
</div>

<div class="present-form-w">
    <div class="present-form">

        <?php
        $form = ActiveForm::begin([
            'id' => 'get-gift-form',
            'enableAjaxValidation' => true,
            'errorCssClass' => 'error',
            'fieldConfig' => [
                'errorOptions' => ['class' => 'error-text']
            ],
            'options' => [
                'class' => 'show-label-on-error-form'
            ]
        ]) ?>
        <div class="radio-btn-w">
            <p class="title"><?= $model->getAttributeLabel('sex'); ?></p>
            <div class="radio-btn gender clearfix">
                <?= $form->field($model, 'sex', [
                    'template' => "{input}\n{error}\n",
                ])
                    ->radioList(\common\models\GiftRequest::getSexList(), [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $radio = Html::beginTag('div', ['class' => 'radio-row clearfix']);
                            $radio .= Html::radio($name, $checked, ['value' => $value, 'id' => 'sex-'.$index]);
                            $radio .= Html::label('<span></span>'.$label, 'sex-'.$index);
                            $radio .= Html::endTag('div');

                            return $radio;
                        }
                    ]) ?>
            </div>
        </div>

        <div class="input-row">
            <div class="input-item">
                <?= $form->field($model, 'receiver'); ?>
            </div>
        </div>

        <div class="input-row">
            <div class="input-item">
                <?= $form->field($model, 'aboutReceiver')
                    ->textarea([
                        'placeholder' => Yii::t('frontend', 'get_gift_aboutReceiver_placeholder')
                    ]); ?>
            </div>
        </div>

        <div class="input-row">
            <div class="input-item">
                <?= $form->field($model, 'giftReason')->textInput([
                'placeholder' => Yii::t('frontend', 'get_gift_giftReason_placeholder')
                    ]); ?>
            </div>
        </div>

        <div class="radio-btn-w">
            <p class="title"><?= $model->getAttributeLabel('giftBudget'); ?></p>
            <div class="radio-btn">
                <?php
                echo Html::beginTag('div', ['class' => 'radio-btns-row clearfix']);
                echo $form->field($model, 'giftBudget', [
                    'template' => "{input}\n{error}\n",
                ])
                    ->radioList(\common\models\GiftRequest::getBudgetList(), [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $radio = '';
                            if ($index % 2 === 0) {
                                $radio .= Html::endTag('div');
                                $radio .= Html::beginTag('div', ['class' => 'radio-btns-row clearfix']);
                            }

                            $radio .= Html::beginTag('div', ['class' => 'radio-row']);
                            $radio .= Html::radio($name, $checked, ['value' => $value, 'id' => 'budget-'.$index]);
                            $radio .= Html::label('<span></span>'.$label, 'budget-'.$index);
                            $radio .= Html::endTag('div');

                            return $radio;
                        }
                    ]);
                echo Html::endTag('div');
                ?>
            </div>
        </div>

        <div class="input-row">
            <div class="input-item">
                <?= $form->field($model, 'aboutGift')
                    ->textarea([
                        'placeholder' => Yii::t('frontend', 'get_gift_aboutGift_placeholder')
                    ]); ?>
            </div>
        </div>

        <div class="input-row">
           <div class="input-item">
                <?= $form->field($model, 'name'); ?>
           </div>
        </div>

        <div class="input-row">
            <div class="input-item">
                <?= $form->field($model, 'phone'); ?>
            </div>
        </div>

        <div class="input-row">
            <div class="input-item">
                <?= $form->field($model, 'email'); ?>
            </div>
        </div>

        <a class="btn-square btn-square-yellow form-submit" href="#">
            <span><?= Yii::t('frontend', 'send'); ?></span>
        </a>

    </div>
    <?php  ActiveForm::end(); ?>
    <!--present-form End-->
</div>
<!--present-form-w End-->
<div class="bunner-img-w">
    <div class="bunner-img clearfix">
        <div class="img-w">
            <img data-big="/img/demo/459x272.png" alt="demo">
        </div>
        <div class="bunner-img-text">
            <h3><?= Yii::t('giftRequestPage', 'Gift certificate') ?></h3>

            <p><?= Yii::t('giftRequestPage', 'We have a variant for girls') ?></p>
            <a class="btn-more-info" href="<?= Url::to(\app\modules\certificate\models\Certificate::getCertificateRoute()) ?>"><?= Yii::t('frontend', 'get more') ?></a>
        </div>
    </div>
</div>
<!--bunner-img-w End-->
