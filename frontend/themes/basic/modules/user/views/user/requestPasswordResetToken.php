<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

?>
<div class="registration-w">
    <p class="main-title"><?= Yii::t('resetPasswordForm', 'chicardi_reset_password_label'); ?></p>
    <div class="registration">
        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'request-password-reset-form',
                'errorCssClass' => 'error',
                'fieldConfig' => [
                    'template' => "{label}\n{error}\n{input}\n{hint}",
                    'errorOptions' => ['class' => 'error-text']
                ]
            ]
        );

        if (Yii::$app->session->hasFlash('passwordResetRequest')) {
            echo \yii\helpers\Html::tag(
                'div',
                Yii::$app->session->getFlash('passwordResetRequest'),
                ['class' => 'flash']
            );
        };
        ?>
        <p><?= Yii::t('resetPasswordForm', 'reset_password_form_label'); ?></p>
        <div class="reg-form">

            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'email') ?>
                </div>
            </div>
            <div class="btn-w">

                <a class="btn-round btn-round__purp form-submit" href="#">
                    <span><?= Yii::t('resetPasswordForm', 'send_email_label'); ?></span>
                </a>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

</div>
