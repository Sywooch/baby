<?php
/**
 * Author: Pavel Naumenko
 */
?>
<div class="article-w">
    <h1><?= \Yii::t('frontend', 'Gift certificates'); ?></h1>
    <div class="article">
        <div class="post">
            <p><?= \Yii::t('giftCertificates', 'Gift_certificates_desc'); ?></p>
        </div>
        <div class="img-big">
            <img data-big="/img/article/desc/sert-big.jpg" data-small="/img/article/mob/sert-small.jpg" src="" alt="img1"/>
        </div>
        <div class="post">
            <p><?= \Yii::t('giftCertificates', 'Gift_certificates_desc_2'); ?></p>
        </div>
        <?= \frontend\modules\certificate\widgets\Widget::widget(); ?>
        <div class="post">
            <p><?= \Yii::t('giftCertificates', 'Gift_certificates_nominal_package'); ?></p>
        </div>
    </div>
</div>

<div class="important-info-w important-info__delivery important-info-w__last">
    <h1><?= \Yii::t('giftCertificates', 'Gift_certificates_info'); ?></h1>
    <div class="important-info clearfix">
        <ul>
            <li><i>1</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_1'); ?> </li>
            <li><i>2</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_2'); ?> </li>
            <li><i>3</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_3'); ?> </li>
            <li><i>4</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_4'); ?> </li>
            <li><i>5</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_5'); ?> </li>
            <li><i>6</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_6'); ?> </li>
            <li><i>7</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_7'); ?> </li>
            <li><i>8</i><?= \Yii::t('giftCertificates', 'Gift_certificates_info_8'); ?> </li>
        </ul>
    </div>
</div>
