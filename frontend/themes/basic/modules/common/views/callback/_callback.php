<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $form \app\modules\common\forms\CallbackForm
 */
use yii\widgets\ActiveForm;

?>
<div id="popup-phone-my" class="popup">
    <h2><?= Yii::t('frontend', 'Request callback') ?></h2>
    <?php
    $aForm = ActiveForm::begin(
        [
            'id' => 'callback-form',
            'errorCssClass' => 'error',
            'options' => ['class' => 'ajax-form'],
            'action' => \app\modules\common\forms\CallbackForm::getFormUrl(),
            'fieldConfig' => [
                'errorOptions' => ['class' => 'error-text']
            ]
        ]
    ) ?>
    <div class="input-row">
        <div class="row-i clearfix">
            <div class="input-item cod">
                <span class="plus-cod">+</span>
                <input name="code" id="code" type="text" value="380" readonly>
            </div>

            <div class="input-item number">
                <?= $aForm->field($form, 'phone', ['template' => "{input}\n{error}"]) ?>
            </div>
        </div>

    </div>

    <a href="#" class="form-submit btn-round btn-round__purp">
        <span><?= Yii::t('frontend', 'Call me') ?></span>
    </a>
    <?php ActiveForm::end(); ?>
</div>
