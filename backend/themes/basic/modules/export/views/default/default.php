<?php
/**
 * Author: Pavel Naumenko
 */
use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Экспорт</h3>
    </div>
    <div class="panel-body">
        <?php
        echo Html::beginForm();
        echo Html::beginTag('p');
        echo Html::submitButton('Експорт', ['class' => 'btn btn-info']);
        echo Html::endTag('p');
        echo Html::tag('p', 'Выберите поля для экспорта:');
        echo \common\models\StoreProduct::getExportColumns();
        echo Html::endForm();
        ?>
    </div>
</div>

