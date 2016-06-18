<?php
/**
 * Author: Pavel Naumenko
 */
?>
<div class="cabinet-menu clearfix">
    <p class="hello"> <?= Yii::t('frontend', 'Hello_header') ?>, <a href="<?= \frontend\models\DummyModel::getProfileLink(); ?>">
            <?= \yii\helpers\Html::encode(strip_tags(Yii::$app->user->identity->name)); ?>
        </a></p>
    <p class="private">
        <?php /* <a href="#" class="bonus btn-more-info">Мои бонусы</a> */ ?>
        <a href="<?= \frontend\models\DummyModel::getLogoutLink(); ?>" data-method="post" class="exit btn-more-info"><?= Yii::t('frontend', 'logout') ?></a>
    </p>
</div>
