<?php
/**
 * Author: Pavel Naumenko
 */
use yii\helpers\Url;

?>
<div class="cabinet-w clearfix">
    <p class="main-title">My chicardi</p>
    <div class="col user">
        <?= $this->render('_user_avatar', ['src' => false]); ?>
        <?= $this->render('_profile_user_info', compact('user')); ?>
    </div>
</div>

<?php
if (!$user->is_in_club) {
    ?>
    <div class="banner-yellow">
        <p><?= Yii::t('profile', 'join_club_text'); ?></p>
        <a class="btn-round btn-round__purp-black" href="<?= Url::to(\frontend\models\DummyModel::getBlogUrl()); ?>">
            <span><?= Yii::t('profile', 'join_club_label'); ?></span>
        </a>
    </div>
<?php
}
?>

<div class="wish-list-w">
    <?= \frontend\modules\favorite\widgets\favoriteList\Widget::widget(); ?>


    <?= \frontend\modules\sales\widgets\profileRandomSale\Widget::widget(); ?>
</div>
