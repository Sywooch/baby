<?php
/**
 * Author: Pavel Naumenko
 *
 * @var integer $type
 */

$model = new \backend\modules\blog\models\BlogArticle();
echo $model->getContentByType($type);
