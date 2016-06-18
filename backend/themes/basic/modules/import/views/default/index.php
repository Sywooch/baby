<?php
/**
 * Author: Pavel Naumenko
 */
use yii\widgets\ActiveForm;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Экспорт</h3>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        echo $form->field($model, 'file')->fileInput();
        ?>
       <button class="btn btn-info">Загрузить</button>
        <?php ActiveForm::end()
        ?>
    </div>
</div>
