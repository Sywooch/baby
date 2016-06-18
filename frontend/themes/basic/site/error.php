<?php

use frontend\assets\AppAsset;
use frontend\assets\HeadAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="sf_wrapp">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="text-404-w">
        <div class="error">
            <p><?= nl2br(Html::encode($message)); ?></p>
        </div>
    </div>

    <div class="home-btn-w">
        <a class="btn-round btn-round__purp" href="<?= Url::toRoute('/'); ?>">
            <span><?= Yii::t('frontend', 'Back to main page'); ?></span>
        </a>
    </div>
</div>
