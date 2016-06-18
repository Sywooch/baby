<?php
/**
 * Author: Pavel Naumenko
 *
 *  * @var $form NewsSubscribeForm
 */
use app\modules\common\forms\NewsSubscribeForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$success = (isset($success) && $success) ? true : false;
?>
<div class="news-subscribe">
        <?php
        ActiveForm::begin([
            'action' => NewsSubscribeForm::getFormUrl(),
            'options' => [
                'class' => $success ? 'input-search-row ajax-form': 'input-search-row send ajax-form'
            ]
        ]);
        echo Html::activeTextInput(
            $form,
            'email',
            [
                'placeholder' => \Yii::t('frontend', 'enter email'),
                'class' => 'search-field',
            ]
        ) ?>

        <div class="metter">
            <span></span>
        </div>
        <input value="" class="search-btn" type="submit">
    <?php ActiveForm::end(); ?>
    <?= Html::error($form, 'email', ['class' => 'error']); ?>
</div>
