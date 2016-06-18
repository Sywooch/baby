<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $pageSize int
 */
use yii\helpers\Url;

?>
<div class="btn-group pull-right">
    <a href="<?= Url::current(['pageSize' => 20]) ?>" class="<?= $pageSize == 20 ? 'active' : '' ?> btn btn-default">20</a>
    <a href="<?= Url::current(['pageSize' => 40]) ?>" class="<?= $pageSize == 40 ? 'active' : '' ?> btn btn-default">40</a>
    <a href="<?= Url::current(['pageSize' => 0]) ?>" class="<?= $pageSize == 0 ? 'active' : '' ?> btn btn-default">Все</a>
</div>
