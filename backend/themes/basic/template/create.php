<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model \backend\components\BackModel
 */


$this->params['breadcrumbs'] = [
    [
        'label' => $model->getBreadCrumbRoot(),
        'url' => ['index']
    ],
    'Создание'
];

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Создание</h3>
        </div>
        <?php echo $this->render('_form', ['model' => $model]) ?>
    </div>
