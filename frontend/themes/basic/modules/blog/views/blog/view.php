<?php
/**
 * Author: Pavel Naumenko
 */
use frontend\components\SmartFPM;
use frontend\modules\blog\models\BlogRubric;
use yii\helpers\Html;
use yii\helpers\Url;

$blogUrl = Url::previous('blog') ? Url::previous('blog') : Url::to(BlogRubric::getBlogRoute());
$shareImg = $model->file_id
    ? Url::to(\metalguardian\fileProcessor\helpers\FPM::src($model->file_id, 'seo', 'default'), true)
    : '';
\frontend\widgets\openGraphMetaTags\Widget::widget([
    'title' => $model->label,
    'description' => $model->description,
    'url' => Url::to(Url::current(), true),
    'image' => $shareImg
]); ?>
<img class="hidden" itemprop="image" src="<?= $shareImg; ?>"/>
<div class="breadcrumbs-w">
    <div class="back-catalog-w">
        <a class="btn-back-catalog" href="<?= $blogUrl; ?>">
            <?= Yii::t('frontend', 'Back to blog'); ?>
            <i class="y-circle"></i>
        </a>
    </div>
    <div class="breadcrumb hide">
        <a href="<?= Url::toRoute('/'); ?>"><?= Yii::t('frontend', 'Store Chicardi'); ?></a>
        /
        <a href="<?= Url::to(BlogRubric::getBlogRoute()); ?>">
            <?= \Yii::t('frontend', 'Chicardi club'); ?>
        </a>
        /
        <span><?= $model->label; ?></span>
    </div>
</div>

<div class="article-w">
    <h1><?= $model->label; ?></h1>


    <div class="article blog-article">
        <?php
        echo $model->getContentByType();

        ?>
        <?php /*
                <div class="post">
                    <p>Декабрь - месяц подведения итогов года: рабочих, личных - очень разных.
                        Мы свои итоги начинаем, конечно, с нашей визитной карточки - с платьев :)
                        Дорогие девочки, вы на протяжение 12 месяцев брали в аренду наши наряды для
                        самых разных мероприятий. Мы составили подборку платьев, которые безоговорочно
                        полюбились большинству из вас. Сегодня публикуем часть первую, о длине макси.
                    </p>
                </div>
                <div class="img-big">
                    <img src="img/article/mob/img-1.png" alt="img1"/>
                </div>
                <div class="post">
                    <p>Декабрь - месяц подведения итогов года: рабочих, личных - очень разных.
                        Мы свои итоги начинаем, конечно, с нашей визитной карточки - с платьев :)
                        Дорогие девочки, вы на протяжение 12 месяцев брали в аренду наши наряды для
                        самых разных мероприятий. Мы составили подборку платьев, которые безоговорочно
                        полюбились большинству из вас. Сегодня публикуем часть первую, о длине макси.
                    </p>
                </div>
                <div class="img-grid clearfix">
                    <div class="img-right">
                        <img data-big="img/article/mob/img-4.png" data-small="img/article/mob/img-4.png" alt="img4"/>
                    </div>
                    <div class="img-left">
                        <img data-big="img/article/mob/img-2.png" data-small="img/article/mob/img-2.png" alt="img2"/>
                        <img data-big="img/article/mob/img-3.png" data-small="img/article/mob/img-3.png" alt="img3"/>
                    </div>
                </div>
                <div class="post">
                    <p>Декабрь - месяц подведения итогов года: рабочих, личных - очень разных.
                        Мы свои итоги начинаем, конечно, с нашей визитной карточки - с платьев :)
                        Дорогие девочки, вы на протяжение 12 месяцев брали в аренду наши наряды для
                        самых разных мероприятий. Мы составили подборку платьев, которые безоговорочно
                        полюбились большинству из вас. Сегодня публикуем часть первую, о длине макси.
                    </p>
                </div>
        */ ?>

    </div>
</div>

<div class="reviews-w">
    <div class="reviews-i">
        <?=
        \ijackua\sharelinks\ShareLinks::widget(
            [
                'viewName' => '@app/themes/basic/shares/default'
            ]
        );
        ?>
        <?= \frontend\modules\blog\widgets\comment\Widget::widget([
            'articleId' => $model->id
        ]) ?>
    </div>

</div>

<?= \frontend\modules\blog\widgets\last4Article\Widget::widget([
    'idNot' => $model->id,
    'view' => 'default_without_grayscale',
    'order' => 'RAND()'
]); ?>
