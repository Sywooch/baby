<?php
/**
 * Author: Pavel Naumenko
 */
use backend\modules\store\models\StoreProduct;

?>
<a class="crop-link btn btn-xs btn-default pull-right" data-toggle="modal" href="<?= StoreProduct::getCropUrl(['id' => '']) ?>" {dataKey} data-target=".modal-hidden">
    <i class="glyphicon glyphicon glyphicon-scissors file-icon-large text-success"></i>
</a>
