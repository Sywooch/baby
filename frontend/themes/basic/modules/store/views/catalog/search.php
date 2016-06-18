<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Url;

$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
?>
    <div class="breadcrumb">
        <div class="breadcrumb">
            <a href="<?= Url::home() ?>"><?= \Yii::t('front', 'Home') ?></a> » <a href="#"><?= \Yii::t('front', 'Search') ?></a>
        </div>
    </div>
    <h1><span class="h1-top"><?= \Yii::t('front', 'Search by query') ?> "<?= $search ?>"</span></h1>
    <div class="product-filter">
        <div class="display">
            <label><?= \Yii::t('front', 'Display:') ?></label>
            <p><span id="list" class="list_on"></span> <span id="grid" onclick="display('grid');"></span></p>
        </div>
        <?= \app\modules\store\widgets\sortItems\Widget::widget() ?>
        <?= \app\modules\store\widgets\limitItems\Widget::widget() ?>
    </div>
<?= \frontend\components\CatalogListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}',
    'itemOptions' => ['class' => 'catalog-item'],
    'itemView' => '_item',
    'options' => ['class' => 'product-list']
]);
?>
<div class="pagination-wrap">
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $dataProvider->pagination,
]);
?>
</div>
