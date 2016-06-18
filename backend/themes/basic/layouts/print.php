<?php
/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\PrintAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="container">
        <a class="no-print" href="javascript:window.print()"><h1>Печать</h1></a>

        <div class="content">
            <img class="img" src="<?= Yii::$app->view->theme->baseUrl ?>/img/logo-f.png"/>
        </div>
        <?= $content; ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
