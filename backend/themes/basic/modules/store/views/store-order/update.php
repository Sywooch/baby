<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model \backend\components\BackModel
 */
use \kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;

$this->params['breadcrumbs'] = [
    [
        'label' => $model->getBreadCrumbRoot(),
        'url' => ['index']
    ],
    'Редактирование'
];

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Редактирование</h3>
        </div>
        <?php echo $this->render('_form', ['model' => $model]) ?>
    </div>
