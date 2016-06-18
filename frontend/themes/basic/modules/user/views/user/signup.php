<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

?>
<div class="registration-w">
    <p class="main-title"><?= Yii::t('signupForm', 'Registration_label'); ?></p>

    <div class="registration">
        <?= \frontend\modules\user\widgets\authChoice\Widget::widget([
            'baseAuthUrl' => ['/user/user/auth'],
            'popupMode' => false,
            'preLabel' => \Yii::t('loginForm', 'signup_with_social'),
            'postLabel' => \Yii::t('loginForm', 'signup_fill_fields'),
        ]) ?>
        <?php $form = ActiveForm::begin([
            'id' => 'form-signup',
            'errorCssClass' => 'error',
            'fieldConfig' => [
                'template' => "{label}\n{error}\n{input}\n{hint}",
                'errorOptions' => ['class' => 'error-text']
            ]
        ]); ?>

        <div class="reg-form">
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'name'); ?>
                </div>

            </div>
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'surname'); ?>
                </div>

            </div>
            <div class="input-row">
                <div class="input-email">
                    <?= $form->field($model, 'email'); ?>
                </div>

            </div>
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'password')->passwordInput(); ?>
                </div>
            </div>
        </div>

        <p><?= Yii::t('signupForm', 'data_which_simplifies_order_label'); ?></p>
        <div class="reg-form">
            <div class="input-row">
                <?= $form->field($model, 'phone', ['template' => "{label}"]); ?>
                <div class="row-i clearfix">
                    <div class="input-item cod">
                        <span class="plus-cod">+</span>
                        <input name="ContactForm[cod]" id="ContactForm_cod" type="text" value="380" readonly>
                    </div>

                    <div class="input-item number">
                        <?= $form->field($model, 'phone', ['template' => "{input}\n{error}"]); ?>
                    </div>
                </div>
            </div>
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'city'); ?>
                </div>

            </div>
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'address'); ?>
                </div>

            </div>
            <div class="input-row">
                <div class="input-email">
                    <?= $form->field($model, 'secondary_address'); ?>
                </div>

            </div>
            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'discount_card', ['inputOptions' => ['class' => 'input__yell']]); ?>
                </div>
            </div>
        </div>

        <div class="btn-w">
            <a class="btn-round btn-round__purp form-submit" href="#">
                <span><?= Yii::t('signupForm', 'register_me_label'); ?></span>
            </a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
