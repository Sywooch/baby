<?php
/**
 * Author: Pavel Naumenko
 *
 */

?>
<div id="popup-favorite" class="popup favorite">
    <h2><?= Yii::t('frontend', 'add_to_favorite_popup_label') ?></h2>

    <p>
        <?= Yii::t('frontend', 'add_to_favorite_popup_unregistered') ?>
    </p>

    <div class="btns clearfix">
        <a class="btn-round mychick" href="<?= \frontend\models\DummyModel::getSignupLink(); ?>"><span><?= Yii::t('loginForm', 'ref_favorite'); ?></span></a>
        <a href="<?= \frontend\models\DummyModel::getLoginLink(); ?>" class="btn-round btn-round__yell"><span><?= Yii::t('loginForm', 'login'); ?></span></a>
    </div>

</div>
