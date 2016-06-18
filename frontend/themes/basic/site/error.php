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
$this->context->layout = false;
AppAsset::register($this);
HeadAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE HTML>
<html lang="en-US" class="page-404-w">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="format-detection" content="telephone=no">
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body class="page-404">
<?php $this->beginBody() ?>

<!--<div id="home">-->
<div class="sf_wrapp">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="text-404-w">
        <div class="text-404">
            <p><?= $exception->statusCode; ?></p>
        </div>
        <div class="error">
            <p><?= nl2br(Html::encode($message)); ?></p>
        </div>
    </div>

    <div class="home-btn-w">
        <div class="img-w">
            <img src="/img/logo.png" alt="Chicardi"/>
        </div>
        <a class="btn-round btn-round__purp" href="<?= Url::toRoute('/'); ?>">
            <span><?= Yii::t('frontend', 'Back to main page'); ?></span>
        </a>
    </div>
</div>

<footer>
    <div class="footer-bottom">
        <div class="footer-bottom-i clearfix">
            <p class="copyright">
                Chicardi.com &copy; 2012-<?= date('Y'); ?>
                <br/>
                <?= \Yii::t('frontend', 'All rights reserved.') ?>
            </p>

            <div class="vintage">
                <span>with love by</span>
                <a href="<?= Url::to('http://vintage.com.ua'); ?>"><i class="icon-vintage"></i><i class="icon-vintage-h"></i></a>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
