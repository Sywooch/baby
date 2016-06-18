<?php
/**
 * Author: Pavel Naumenko
 */
?>
<div class="article-w">
    <h1>оплата и доставка</h1>
</div>

<?= \frontend\modules\common\widgets\payAndDeliveryTable\Widget::widget(); ?>
<div class="important-info-w important-info__delivery">

    <div class="important-info  clearfix">
        <ul>
            <li><i>1</i><?= Yii::t('payAndDelivery', 'info_1') ?></li>
            <li><i>2</i><?= Yii::t('payAndDelivery', 'info_2') ?></li>
            <li><i>3</i><?= Yii::t('payAndDelivery', 'info_3') ?></li>
            <li><i>4</i><?= Yii::t('payAndDelivery', 'info_4') ?></li>
            <li><i>5</i><?= Yii::t('payAndDelivery', 'info_5') ?></li>
            <li><i>6</i><?= Yii::t('payAndDelivery', 'info_6') ?></li>
            <li><i>7</i><?= Yii::t('payAndDelivery', 'info_7') ?></li>
            <li><i>8</i><?= Yii::t('payAndDelivery', 'info_8') ?></li>
            <li><i>9</i><?= Yii::t('payAndDelivery', 'info_9') ?></li>
            <li><i>10</i><?= Yii::t('payAndDelivery', 'info_10') ?></li>
        </ul>
    </div>
</div>

<div class="article__border">
    <div class="post">
        <p><?= Yii::t('payAndDelivery', 'delivery_details') ?></p>
    </div>
</div>

<div class="article-w article-w__padding">
    <div class="article">
        <h1><?= Yii::t('payAndDelivery', 'delivery_other_countries') ?></h1>
        <div class="post">
            <p><?= Yii::t('payAndDelivery', 'delivery_other_countries_desc') ?></p>
        </div>

    </div>
</div>
