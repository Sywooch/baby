<?php
/**
 * Author: Pavel Naumenko
 *
 * @var Sales[] $models
 * @var Sales $model
 */
use frontend\modules\sales\models\Sales;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="sale clearfix">
    <i class="letter-i" data-top-bottom="transform:translate(0,-300px);"
        data-bottom-center="transform:translate(0,600px);"
        style="background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIIAAAC0CAYAAABYIPRNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDgxMzNERkI5QTNBMTFFNEIxQUZCRUQ4NzVFRkIwQzUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDgxMzNERkM5QTNBMTFFNEIxQUZCRUQ4NzVFRkIwQzUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0ODEzM0RGOTlBM0ExMUU0QjFBRkJFRDg3NUVGQjBDNSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0ODEzM0RGQTlBM0ExMUU0QjFBRkJFRDg3NUVGQjBDNSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PqnHPiUAAAhqSURBVHja7J0Nk6o6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');"></i>
    <i class="letter-c-small" data-top-bottom="transform:translate(0,-600px);"
        data-bottom-center="transform:translate(0,200px);"
        style="background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKsAAACYCAYAAACbBhkaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NURGNUQzQjQ5QTNBMTFFNEEwNEQ4RjQ5RkJDRDNDMzkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NURGNUQzQjU5QTNBMTFFNEEwNEQ4RjQ5RkJDRDNDMzkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1REY1RDNCMjlBM0ExMUU0QTA0RDhGNDlGQkNEM0MzOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1REY1RDNCMzlBM0ExMUU0QTA0RDhGNDlGQkNEM0MzOSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnmGhCIAAAnbSURBVHja7J2Lltso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');"></i>

    <?php
    if (isset($models[0])) {
        $content = Html::tag('span', $models[0]->label);
        $content .= $models[0]->description;
        if (empty($models[0]->content)) {
            $url = Url::to(Sales::getViewAllSalesRoute());
        } else {
            $url = Sales::getViewUrl(['alias' => $models[0]->alias]);
        }

        echo Html::a($content, $url, ['class' => 'btn-stock']);
    }
    ?>
    <div class="club-w clearfix">
        <img class="bg-club" src="" data-big="/img/bg-club.jpg" alt=""/>
        <a class="club" href="<?= Url::to(\frontend\models\DummyModel::getBlogUrl()); ?>">
            <span class="club-title"><?= Yii::t('mainPage', 'Connect to our') ?> <span class="club-title-strong"><?= Yii::t('frontend', 'club Chicardi') ?></span></span>
					<span class="club-text"><?= Yii::t('mainPage', 'Connect_to_club_text') ?></span>
            <span class="club-text"><?= Yii::t('mainPage', 'Register_in_club_text') ?></span>
            <span class="want"><span class="club-want-strong"><?= Yii::t('mainPage', 'I want!') ?></span></span>
        </a>
    </div>

    <?php
    if (isset($models[1])){ ?>
        <div class="btn-yellow-wrapper">
            <a class="btn-bunner-yellow btn-bunner-white" href="<?=
                 empty($models[1]->content)
                    ? Url::to(Sales::getViewAllSalesRoute())
                    : Sales::getViewUrl(['alias' => $models[1]->alias]);
            ?>">
                <span class="btn-yellow-wrapper-strong"><?= $models[1]->label; ?></span>
                <span><?= $models[1]->description; ?></span>
                <i class="more"><?= Yii::t('frontend', 'get more'); ?></i>
            </a>
        </div>
    <?php } ?>
    <?php /*
    <div class="way">
        <p class="main-title">Экскурсия по сайту</p>

        <p class="way-text">Мы собрали для тебя самые красивые и полезные штуки со всего мира! Мы тебе раскажем что и
            где у нас лежит</p>
        <a class="btn-round btn-round__purp" href="#"><span>в путь</span></a>
        <!--<img class="bg-way" src="/img/bg-way.png" alt="bg-way"/>-->
        <i class="bg-way"/></i>
    </div>
    */ ?>
</div>
