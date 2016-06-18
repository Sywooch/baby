<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>
<div class="registration-w">
    <p class="main-title"><?= Yii::t('loginForm', 'chicardi_login_label'); ?></p>
    <div class="registration">
        <?= \frontend\modules\user\widgets\authChoice\Widget::widget([
            'baseAuthUrl' => ['/user/user/auth'],
            'popupMode' => false,
        ]) ?>

        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'login-form',
                'errorCssClass' => 'error',
                'fieldConfig' => [
                    'template' => "{label}\n{error}\n{input}\n{hint}",
                    'errorOptions' => ['class' => 'error-text']
                ]
            ]
        );
        ?>
        <div class="reg-form">

            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'email') ?>
                </div>
            </div>
            <div class="input-row input-row__padding">
                <div class="input-item">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
            </div>

            <div class="forget-pass-w">
                <a class="btn-forget-pass" href="<?= \frontend\models\DummyModel::getPasswordResetLink(); ?>">
                    <?= Yii::t('loginForm', 'forgot_pass_label'); ?>
                </a>
            </div>

            <div class="btn-w">

                <a class="btn-round btn-round__purp form-submit" href="#">
                    <span><?= Yii::t('loginForm', 'login'); ?></span>
                </a>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <p class="text-reg">
            <span> <?= Yii::t('loginForm', 'first_time_here_label'); ?></span>
            <a class="btn-more-info" href="<?= \frontend\models\DummyModel::getSignupLink(); ?>">
                <?= Yii::t('loginForm', 'register_if_first_time_label'); ?>
            </a>
        </p>

    </div>

</div>
