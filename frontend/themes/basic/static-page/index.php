<?php
/**
 * @var $model \common\models\StaticPage
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="breadcrumb">
    <a href="<?= Url::home() ?>"><?= \Yii::t('front', 'Home') ?></a> »
    <a href="#"><?= $model->label ?></a>
</div>
<h1><span class="h1-top"><?= $model->label ?></span></h1>
<div class="information_content">
    <?= $model->content ?>
    <?php if ($model->id == 2) { ?>
        <div class="information_content">
            <h2><?= \Yii::t('front', 'Our Location') ?></h2>
            <div class="map">
                <iframe width="432" height="225" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.de/maps?f=q&amp;source=s_q&amp;hl=de&amp;geocode=&amp;q=Wildensteiner+Stra%C3%9Fe+6,+10318,+Berlin&amp;aq=0&amp;oq=Wildensteiner+Str.+6,+10318&amp;sll=52.08049,13.699819&amp;sspn=0.152545,0.348473&amp;ie=UTF8&amp;hq=&amp;hnear=Wildensteiner+Stra%C3%9Fe+6,+10318+Berlin&amp;t=m&amp;ll=52.480271,13.523569&amp;spn=0.005881,0.018497&amp;z=15&amp;output=embed"></iframe>
            </div>
            <div class="contact-info">
                <ul>
                    <li class="item_1"><?= Yii::$app->config->get('address') ?></li>
                    <li class="item_2"><?= Yii::$app->config->get('contact_phone_1') ?></li>
                    <li class="item_2"><?= Yii::$app->config->get('contact_phone_2') ?></li>
                </ul>
            </div>
            <div class="social-info"> <span><?= \Yii::t('front', 'Find us on') ?></span>
                <ul>
                    <li><a class="twitter" href="#" title="Twitter">&nbsp;</a></li>
                    <li><a class="facebook" href="#" title="Facebook">&nbsp;</a></li>
                    <li><a class="youtube" href="#" title="Youtube">&nbsp;</a></li>
                    <li><a class="vimeo" href="#" title="Vimeo">&nbsp;</a></li>
                </ul>
            </div>
            <h2><?= \Yii::t('front', 'Contact Form') ?></h2>
            <div class="content">
                <p><?= \Yii::t('front', 'contact_form_text') ?></p>
                <span class="contact-form-message"><?= Yii::$app->session->getFlash('contact-form-message') ?></span>
                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                    'fieldConfig' => [
                        'template' => '<span class="small">{label}:</span><br>{input}{error}',
                    ],
                ]); ?>
                <div class="one-two">
                    <?= $form->field($contactForm, 'name')->textInput(['style' => 'width:314px']) ?>
                </div>
                <div class="two-two">
                    <?= $form->field($contactForm, 'email')->textInput(['style' => 'width:314px']) ?>
                </div>
                <div>
                <?= $form->field($contactForm, 'body')->textArea(['style' => 'width:98%', 'rows' => 6]) ?>
                </div>
                <div class="buttons">
                    <div class="right">
                        <input type="submit" value="Continue" class="button">
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php } ?>
</div>
