<?php
/**
 * Author: Pavel Naumenko
 */
use yii\widgets\ActiveForm;

?>
<div id="popup-cab-edit" class="popup-cab-edit-W">
    <div class="popup-cab-edit">
        <?php /*
        <div class="edit-foto-w">
            <a class='btn-edit-foto' id="user-foto" href="#"><span>обновить фотографию</span></a>
            <div class="input-row input-row__border">
                <div class="error-text">
                    Это поле необходимо заполнить
                </div>

                <div class="input-item">
                    <input name="ContactForm[city]" id="ContactForm_city" type="text">
                </div>
            </div>
        </div>
        */ ?>
        <?php $form = ActiveForm::begin([
            'id' => 'form-profile',
            'action' => \frontend\models\DummyModel::getProfileUpdateLink(),
            'options' => [
                'class' => 'ajax-form'
            ],
            'enableAjaxValidation' => true,
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
        </div>
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

            <a class="btn-round btn-round__purp form-submit" href="#">
                <span><?= Yii::t('profile', 'save_form_label'); ?></span>
            </a>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
