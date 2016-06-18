<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */
$resetLink = \yii\helpers\Url::to(\frontend\models\DummyModel::getPasswordResetSetNewPassworLink(['token' => $user->password_reset_token]), true);
?>
<?= \Yii::t('resetPasswordForm', 'hello_in_email') . ' ' . $user->name ?>,


<?= \Yii::t('resetPasswordForm', 'Follow the link below to reset your password') . ': ' ?>


<?= \yii\helpers\Html::a($resetLink, $resetLink); ?>
