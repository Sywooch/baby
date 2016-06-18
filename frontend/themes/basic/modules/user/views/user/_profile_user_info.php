<?php
/**
 * Author: Pavel Naumenko
 *
 * @var \common\models\User $user
 */
use yii\helpers\Html;

?>
<div class="user-descr">
    <div class="user-descr-row">
        <p class="in-club"><?= Yii::$app->user->identity->is_in_club ? Yii::t('profile', 'in_club_label') : null; ?></p>
        <p class="name">
            <span><?= Html::encode(strip_tags($user->name)); ?></span>
            <span><?= Html::encode(strip_tags($user->surname)); ?></span>
        </p>
    </div>
    <div class="user-descr-row tel-w">
        <p class="tel"> <?= Yii::t('profile', 'Phone'); ?> <span><?= Html::encode($user->phone); ?></span></p>
    </div>

    <div class="user-descr-row">
        <p class="address-title"><?= Yii::t('profile', 'Address') ?> </p>
        <p  class="address"> <?= Html::encode(strip_tags($user->address)); ?></p>
    </div>

    <a class="btn-round btn-round__purp ajax-link" href="<?= \frontend\models\DummyModel::getProfileUpdateLink(); ?>"><span><?= Yii::t('profile', 'Update data') ?></span></a>
</div>
