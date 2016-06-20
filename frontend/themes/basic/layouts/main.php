<?php
use app\modules\common\forms\NewsSubscribeForm;
use app\modules\store\models\StoreCategory;
use frontend\assets\GMapAsset;
use frontend\assets\HeadAsset;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
/*HeadAsset::register($this);
GMapAsset::register($this);*/
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html class="vis-block" lang="ru">
    <?= $this->render('@frontend/themes/basic/layouts/_head'); ?>
    <body>
    <?php $this->beginBody() ?>
    <div id="container">
        <div id="header">
            <div id="logo"><a href="<?= Url::home() ?>"><img src="/image/logo.png" title="Things for Cuties" alt="Things for Cuties"></a></div>
            <div id="header_right">
                    <div id="language">
                    </div>
                <div id="search">
                    <div class="button-search" data-url="<?= StoreCategory::getSearchUrl() ?>"></div>
                    <input type="text" name="search" placeholder="Search" value="">
                </div>
                <?= \frontend\widgets\headerCart\Widget::widget() ?>
                <div id="bottom_right">
                    <p id="welcome"><?= Yii::t('front', 'Welcome to our baby shop!') ?></p>
                </div>
            </div>
        </div>
        <?= \frontend\widgets\mainMenu\Widget::widget() ?>
        <?php if (in_array(Yii::$app->controller->id, ['catalog', 'product', 'static-page'])) {
            echo $this->render('//layouts/_sidebar');
        } ?>
        <div id="content">
            <?= $content ?>
        </div>
    </div>

    <?= $this->render('@frontend/themes/basic/layouts/_footer'); ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
