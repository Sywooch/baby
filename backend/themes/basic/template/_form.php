<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model \backend\components\BackModel
 */

use \kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;

$action = isset($action) ? $action : '';
?>


<div class="panel-body">
    <?php
    echo Html::errorSummary(
        $model,
        [
            'class' => 'alert alert-danger'
        ]
    );
    ?>
    <?php
    $form = ActiveForm::begin(
        [
            'action' => $action,
            'type' => ActiveForm::TYPE_VERTICAL,
            'enableClientValidation' => false,
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]
    );
    $rows = $model->prepareForm();

    if (isset($rows['form-set'])) {
        $items = [];
        $i = 0;

        foreach ($rows['form-set'] as $formName => $rowSet) {
            $items[] = [
                'label' => $formName,
                'content' => Form::widget(
                    [
                            'model' => $model,
                            'form' => $form,
                            'columns' => $model->getColCount(),
                            'attributes' => $rowSet
                    ]
                ),
                'active' => $i ? false : true,
                'options' => [
                    'class' => 'tab_'.$i.'_content'
                ],
                'linkOptions' => [
                    'class' => 'tab_'.$i
                ]
            ];
            $i++;
        }

        echo \yii\bootstrap\Tabs::widget(['items' => $items]);

    } else {
        echo Form::widget(
            [
                'model' => $model,
                'form' => $form,
                'columns' => $model->getColCount(),
                'attributes' => $rows
            ]
        );
    }

    ?>
    <div class="row">
        <div class="col-sm-12">
            <?php
            echo Html::resetButton('Сбросить', ['class' => 'btn btn-default']) . ' ' .
                Html::submitButton('Сохранить', ['class' => 'btn btn-primary'])
            ?>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>
