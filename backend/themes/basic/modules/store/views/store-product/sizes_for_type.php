<?php
/**
 * @var $model \backend\modules\store\models\StoreProduct
 * @var $typeId integer
 */

use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;

echo $typeId
    ? MultipleInput::widget([
        'model' => $model,
        'attribute' => 'sizes',
        'allowEmptyList' => true,
        'columns' => [
            [
                'name'  => 'id',
                'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
            ],
            [
                'name'  => 'product_type_size_id',
                'type'  => MultipleInputColumn::TYPE_DROPDOWN,
                'title' => 'Типовой размер',
                'items' => $model->getTypeSizeOptions($typeId)
            ],
            [
                'name'  => 'price',
                'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
                'title' => 'Цена',
            ],
            [
                'name'  => 'old_price',
                'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
                'title' => 'Старая цена',
            ],
            [
                'name'  => 'existence',
                'type'  => MultipleInputColumn::TYPE_CHECKBOX,
                'title' => 'В наличии',
            ]
        ]
    ])
    : 'Выберите тип продукта';
