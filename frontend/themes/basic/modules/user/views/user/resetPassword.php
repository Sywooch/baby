<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

?>
<div class="registration-w">
    <p class="main-title"><?= Yii::t('newPasswordForm', 'new_password_label'); ?></p>
    <div class="registration">
        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'new-password-form',
                'errorCssClass' => 'error',
                'fieldConfig' => [
                    'template' => "{label}\n{error}\n{input}\n{hint}",
                    'errorOptions' => ['class' => 'error-text']
                ]
            ]
        );
        ?>
        <p><?= Yii::t('newPasswordForm', 'new_password_form_label'); ?></p>
        <div class="reg-form">

            <div class="input-row">
                <div class="input-item">
                    <?= $form->field($model, 'password')->passwordInput(); ?>
                </div>
            </div>
            <div class="btn-w">

                <a class="btn-round btn-round__purp form-submit" href="#">
                    <span><?= Yii::t('newPasswordForm', 'reset_password_button_label'); ?></span>
                </a>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

</div>
