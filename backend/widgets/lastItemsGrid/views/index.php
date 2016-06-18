<?php
/**
 * Author: Pavel Naumenko
 */
use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= $label; ?></h3>
    </div>

    <div class="panel-body">
        <?php
        echo \yii\grid\GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => false,
                'columns' => $columns,
            ]
        );
        ?>
    </div>
    <?= Html::tag('p', Html::a('Смотреть все', $buttonsPath.'/index'), ['class' => 'view-all-button']); ?>
</div>
