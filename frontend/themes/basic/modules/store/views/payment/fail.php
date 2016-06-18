<?php
/**
 * Author: Pavel Naumenko
 */

use app\modules\common\forms\NewsSubscribeForm;

$fbLink = Yii::$app->config->get('facebook_link');
$fbLink = $fbLink ? $fbLink : '#';
$instagramLink = Yii::$app->config->get('instagram_link');
$instagramLink = $instagramLink ? $instagramLink : '#';
?>
<div class="registration-w">
    <p class="main-title"><?= Yii::t('frontend', 'Sorry, but your payment is failed!'); ?></p>
    <div class="registration">
        <p><?= Yii::t('frontend', 'Contact us to describe details, or try again later.'); ?></p>
        <p><?= Yii::t('frontend', 'Meanwhile you can connect to our socials'); ?></p>
        <div class="btns-w">
            <a href="<?= $fbLink; ?>" target="_blank" class="btn-round btn-round__light-blue"><span>facebook</span></a>
            <a href="<?= $instagramLink; ?>" target="_blank" class="btn-round btn-round__blue"><span>instagram</span></a>
        </div>
        <p><?= Yii::t('frontend', 'or subscribe to our new products'); ?></p>
        <div class="news-mob">
            <?php echo $this->render('@commonModuleViews/news-subscribe/_form', ['form' => new NewsSubscribeForm()]) ?>
        </div>

    </div>


</div>
<div class="bunner-info">
    <p class="bunner-info-tittle"><?= Yii::t('frontend', 'Connect_to_us_cart_banner_title'); ?>!</p>

    <p><?= Yii::t('frontend', 'Connect_to_us_cart_banner_text'); ?></p>
</div>
