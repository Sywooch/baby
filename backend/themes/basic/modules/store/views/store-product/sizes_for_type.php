<?php
/**
 * @var $model \backend\modules\store\models\StoreProduct
 * @var $typeId integer
 */

use backend\modules\store\components\ProductSizesMultipleInput;

echo $typeId
    ? ProductSizesMultipleInput::widget(['model' => $model, 'typeId' => $typeId])
    : 'Выберите тип продукта';
