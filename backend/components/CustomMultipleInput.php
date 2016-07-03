<?php
/**
 * Created by PhpStorm.
 * User: anatolii
 * Date: 02.02.16
 * Time: 17:09
 */

namespace backend\components;


use backend\modules\store\models\StoreCategory;
use backend\modules\store\models\StoreCategoryFilter;
use backend\modules\store\models\StoreCategoryFilterOption;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
use yii\helpers\Html;

/**
 * Class CustomMultipleInput
 *
 * @package backend\modules\store\components
 */
class CustomMultipleInput extends MultipleInput
{
    public function init()
    {
        $this->attribute = 'typeSizes';
        echo Html::activeHiddenInput($this->model, $this->attribute, ['id' => 'hidden-options','value' => '']);
        $this->allowEmptyList = true;
        $this->columns = [
            [
                'name'  => 'id',
                'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
            ],
            [
                'name'  => 'label',
                'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
                'title' => 'Название',
            ],
            [
                'name'  => 'height',
                'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
                'title' => 'Рост',
            ]
        ];

        parent::init();
    }
}
