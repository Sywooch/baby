<?php
/**
 *
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \backend\components\BackModel
 * Author: Pavel Naumenko
 */
$this->params['breadcrumbs'] = [$searchModel->getBreadCrumbRoot()];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Список</h3>
    </div>

    <div class="panel-body">
        <?php
        echo $searchModel->getButtonsList($dataProvider, $searchModel);

        echo \yii\helpers\Html::tag('div', '', ['class' => 'clearfix']);

        echo $searchModel->getIndexPageGridView($dataProvider, $searchModel);

        ?>
    </div>
</div>

