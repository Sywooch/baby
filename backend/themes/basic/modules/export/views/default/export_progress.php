<?php
/**
 * Author: Pavel Naumenko
 */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Экспорт</h3>
    </div>
    <div class="panel-body">
        <?php
        echo \backend\modules\export\widgets\exportStatus\ExportStatus::widget();
        ?>
    </div>
</div>
