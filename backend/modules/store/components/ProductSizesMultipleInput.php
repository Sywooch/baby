<?php
/**
 * Created by anatolii
 */

namespace backend\modules\store\components;


use backend\modules\store\models\StoreProduct;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;

/**
 * Class ProductSizesMultipleInput
 *
 * @package backend\modules\store\components
 */
class ProductSizesMultipleInput extends MultipleInput
{
    /**
     * @var StoreProduct
     */
    public $model;

    /**
     * @var null|integer
     */
    public $typeId = null;

    /**
     * @var string
     */
    public $attribute = 'sizes';

    /**
     * @var bool
     */
    public $allowEmptyList = true;
    
    
    public function init()
    {
        $this->columns = [
            [
                'name'  => 'id',
                'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
            ],
            [
                'name'  => 'product_type_size_id',
                'type'  => MultipleInputColumn::TYPE_DROPDOWN,
                'title' => 'Типовой размер',
                'items' => $this->model->getTypeSizeOptions($this->typeId)
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
        ];
        
        parent::init();
    }
}
