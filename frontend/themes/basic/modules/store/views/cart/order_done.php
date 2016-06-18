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
    <p class="main-title"><?= Yii::t('frontend', 'Thank you for order'); ?></p>
    <div class="registration">
        <p><?= Yii::t('frontend', 'We will contact you as soon as possible'); ?></p>
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
<?= \frontend\components\PokuponChecker::getPokuponImg(); ?>
<?php
//<!-- Offer Conversion: Chicardi - магазин канц. товаров (UA) -->
if ($order && is_array($order)) {
    ?>
    <iframe src="http://primeadv.go2cloud.org/SL6Az?adv_sub=<?= $order['id'] ?>&amount=<?= $order['sum'] ?>" scrolling="no"
frameborder="0" width="1" height="1"></iframe>
<?php
}
?>
<script>
    (function (js) {
        var scr = document.createElement('script');
        scr.setAttribute("src", js + "?ts=" + (new Date().getTime()));
        scr.setAttribute("async", true);
        document.getElementsByTagName("head")[0].appendChild(scr);
    }("//www.dmpcloud.net/spx/chicardi.com.ua/spx-thy.js"));
</script>
