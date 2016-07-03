<?php

use common\models\StaticPage;

$fbLink = Yii::$app->config->get('facebook_link');
$fbLink = $fbLink ? $fbLink : '#';
$instagramLink = Yii::$app->config->get('instagram_link');
$instagramLink = $instagramLink ? $instagramLink : '#';
$googlePlusLink = Yii::$app->config->get('google_plus_link');
$googlePlusLink = $googlePlusLink ? $googlePlusLink : '#';
?>
<div id="footer">
    <?php /*<div id="footer_top">
        <div class="footer_wrapper">
            <div id="footer_top_content">
                <div id="footer_top_item">
                    <div class="footer_top_item" id="about_us">
                        <h3 class="title_item_1 down"><a href="<?= StaticPage::getAboutUrl() ?>"><?= \Yii::t('front', 'About us') ?></a></h3>
                        <p class="text_item content_item_1 about"><?= Yii::t('front', 'footer_text_about_us') ?></p>
                    </div>
                    <div class="footer_top_item" id="contact_us">
                        <h3 class="title_item_2 down"><a href="<?= StaticPage::getContactsUrl() ?>"><?= \Yii::t('front', 'Contact us') ?></a></h3>
                        <div class="text_item">
                            <p class="info_contact"> <span><?= Yii::t('front', 'footer_text_contact_us') ?></span> </p>
                            <p class="online_contact">
                                <span class="phone"><?= Yii::$app->config->get('contact_phone_1') ?></span>
                                <span class="phone"><?= Yii::$app->config->get('contact_phone_2') ?></span>
                                <?php $email = Yii::$app->config->get('admin_email') ?>
                                <span class="mail">
                                    <a class="color" href="mailto:<?= $email ?>" title="Email"><?= $email ?></a>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="footer_top_item " id="twitter_news">
                        <h3 class="title_item_3 down" ><a href="http://www.twitter.com/Mouseevent">Twitter Feed</a></h3>
                        <div class="text_item content_item_3">
                            <div id="twitter_update_list">
                                <a class="twitter-timeline"  width="232" height="250" data-chrome="nofooter noheader transparent noscrollbar" data-tweet-limit="2" href="https://twitter.com/Mouseevent"  data-widget-id="346591733494202368"></a>
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            </div>
                        </div>
                    </div>
                    <div class="footer_top_item last_footer_item" id="facebook">
                        <h3 class="title_item_4 down"><a href="https://www.facebook.com/mouseevent.berlin.brandenburg">Facebook</a></h3>
                        <div class="text_item content_item_4">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0];
                                    if (d.getElementById(id)) return;
                                    js = d.createElement(s); js.id = id;
                                    js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1&appId=371189259657718";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-like-box" data-href="https://www.facebook.com/mouseevent.berlin.brandenburg" data-width="220" data-height="250" data-show-faces="true" data-stream="false" data-show-border="false" data-header="false"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>*/ ?>
    <div class="footer_wrapper">
        <div id="footer_bottom">
            <div class="footer_bottom_item">
                <h3 class="bottom_item_1 down"><a><?= \Yii::t('front', 'Information') ?></a></h3>
                <ul class="menu_footer_item text_item">
                    <?= \frontend\widgets\footerInformation\Widget::widget() ?>
                </ul>
            </div>
            <?= \frontend\widgets\footerMenu\Widget::widget() ?>
            <?php /*<div class="footer_bottom_item">
                <h3 class="bottom_item_2 down"><a>Customer Service</a></h3>
                <ul class="menu_footer_item text_item">
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Site Map</a></li>
                </ul>
            </div>
            <div class="footer_bottom_item">
                <h3 class="bottom_item_3 down"><a>Extras</a></h3>
                <ul class="menu_footer_item text_item">
                    <li><a href="brands.html">Brands</a></li>
                    <li><a href="gifts.html">Gift Vouchers</a></li>
                    <li><a href="#">Affiliates</a></li>
                    <li><a href="specials.html">Specials</a></li>
                </ul>
            </div>
            <div class="footer_bottom_item">
                <h3 class="bottom_item_4 down"><a>My Account</a></h3>
                <ul class="menu_footer_item text_item">
                    <li><a href="myaccount.html">My Account</a></li>
                    <li><a href="orderhistory.html">Order History</a></li>
                    <li><a href="wishlist.html">Wish List</a></li>
                    <li><a href="#">Newsletter</a></li>
                </ul>
            </div>*/ ?>
            <div class="clear"></div>
        </div>
        <div id="mobile-footer">
            <div class="mobile-footer-menu">
                <h3><?= \Yii::t('front', 'Information') ?></h3>
                <div class="mobile-footer-nav" style="display: none;">
                    <ul>
                        <?= \frontend\widgets\footerInformation\Widget::widget() ?>
                    </ul>
                </div>
                <?= \frontend\widgets\footerMenu\Widget::widget(['mobile' => true]) ?>

                <?php /*<h3>Customer Service</h3>
                <div class="mobile-footer-nav" style="display: none;">
                    <ul>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Site Map</a></li>
                    </ul>
                </div>
                <h3>Extras</h3>
                <div class="mobile-footer-nav" style="display: none;">
                    <ul>
                        <li><a href="brands.html">Brands</a></li>
                        <li><a href="gifts.html">Gift Vouchers</a></li>
                        <li><a href="#">Affiliates</a></li>
                        <li><a href="specials.html">Specials</a></li>
                    </ul>
                </div>
                <h3>My Account</h3>
                <div class="mobile-footer-nav" style="display: none;">
                    <ul>
                        <li><a href="myaccount.html">My Account</a></li>
                        <li><a href="orderhistory.html">Order History</a></li>
                        <li><a href="wishlist.html">Wish List</a></li>
                        <li><a href="#">Newsletter</a></li>
                    </ul>
                </div>*/ ?>
            </div>
        </div>
    </div>
    <div id="footer-text">
        <p>rays.com.ua © <?= date('Y') ?></p>
    </div>
</div>

<div id="popup" class="hidden">

</div>
