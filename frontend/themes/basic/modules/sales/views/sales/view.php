<?php
/**
 * Author: Pavel Naumenko
 *
 * @var \frontend\modules\sales\models\Sales $model
 */
use frontend\components\SmartFPM;
use frontend\modules\sales\models\Sales;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="breadcrumbs-w">
    <div class="back-catalog-w">
        <a class="btn-back-catalog" href="<?= Url::to(Sales::getViewAllSalesRoute()) ?>">
            <?= Yii::t('frontend', 'Back to sales'); ?> <i class="y-circle"></i>
        </a>
    </div>
    <div class="breadcrumb hide">
        <a href="<?= Url::toRoute('/'); ?>"><?= Yii::t('frontend', 'Store Chicardi'); ?> </a>
        /
        <a href="<?= Url::to(Sales::getViewAllSalesRoute()) ?>"><?= \Yii::t('frontend', 'Actions & discounts'); ?> </a>
        /
        <span><?= $model->label; ?></span>
    </div>
</div>

<div class="article-w">
    <h1><?= $model->label; ?></h1>

    <?php if ($model->image_id): ?>
        <div class="article-w article-w__padding">
            <div class="article blog-article">
                <div class="img-big">
                    <?=
                    Html::img(
                        '',
                        [
                            'data-small' => SmartFPM::src($model->image_id, 'sales', 'big'),
                            'data-big' => SmartFPM::src($model->image_id, 'sales', 'big'),
                            'alt' => $model->label
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="article blog-article">
        <div class="post">
            <?= $model->content; ?>
        </div>

    </div>
</div>
<?php /*
<div class="important-info-w important-info__delivery">

    <div class="important-info  clearfix">
        <ul>
            <li><i>1</i>товар Chicardi - главный персонаж</li>
            <li><i>2</i>длительность не менее 30 секунд</li>
            <li><i>3</i>хорошее/сносное качество</li>
            <li><i>4</i>звуковое сопровождение с плюсами/минусами товара </li>
            <li><i>5</i>видео отправляй нам на почту <a href="mailto:info@chicardi.com.ua">info@chicardi.com.ua</a></li>
        </ul>
        <p class="important-info-text">Кто знает, может ты откроешь в себе еще один талант ;) <br />
            Ждём!  </p>

    </div>
</div>
 */ ?>

<?php
if ($model->image_bottom_id):
    ?>
    <div class="article-w article-w__padding">
        <div class="article blog-article">
            <div class="img-big last">
                <?=
                Html::img(
                    '',
                    [
                        'data-small' => SmartFPM::src($model->image_bottom_id, 'sales', 'big'),
                        'data-big' => SmartFPM::src($model->image_bottom_id, 'sales', 'big'),
                        'alt' => $model->label
                    ]
                );
                ?>
            </div>
        </div>
    </div>
<?php
endif;
?>
<?= \frontend\modules\sales\widgets\salesBlock\Widget::widget([
    'idNot' => $model->id,
    'order' => 'RAND()'
]) ?>
