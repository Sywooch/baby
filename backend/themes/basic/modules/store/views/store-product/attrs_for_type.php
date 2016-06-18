<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $typeId integer
 */

echo $typeId
    ? (new \backend\modules\store\models\StoreProduct())->getOptionListAsHtml($typeId)
    : 'выберите тип продукта';
