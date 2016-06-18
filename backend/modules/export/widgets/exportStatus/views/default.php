<?php
/**
 * Author: Pavel Naumenko
 */
?>
<div ng-app="exportProgress" ng-controller="exportProcessCtrl">
    <div class="progress" ng-hide="data.isExported">
        <div class="active progress-bar progress-bar-success progress-bar-striped"
            ng-init="init('/export/default/get-export-status')"
            role="progressbar"
            aria-valuenow="{{data.percentage}}"
            aria-valuemin="0"
            aria-valuemax="100" style="width: {{data.percentage}}%">
        </div>
    </div>

    <div ng-show="data.isExported">
        <div class="alert alert-success">
            <strong>Экспорт завершен!</strong> <a href="{{data.fileHref}}">Скачать файл</a>
        </div>

        <a href="<?= \yii\helpers\Url::to('/export/default/index'); ?>">Назад к выбору атрибутов</a>
    </div>
</div>

