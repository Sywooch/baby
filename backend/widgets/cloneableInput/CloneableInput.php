<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\cloneableInput;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class CloneableInput
 *
 * @package backend\widgets\cloneableInput
 */
class CloneableInput extends Widget
{
    /**
     * @var
     */
    public $model;

    /**
     * @var
     */
    public $attribute;

    /**
     * @var
     */
    public $fieldToAppend;

    /**
     * @var
     */
    public $itemToCount;

    /**
     * @var
     */
    public $itemName;

    /**
     * @var string|array $inputName
     */
    public $inputName;

    /**
     * @var int
     */
    public $maxRowsCount = 100;

    /**
     * @var bool
     */
    public $sortable = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->validateConfig();

        $isMultipleInputs = is_array($this->inputName);

        $itemsCount = count($this->model->{$this->attribute});

        $itemToCountClass = str_replace('.', '', $this->itemToCount);
        $fieldToAppendClass = str_replace('.', '', $this->fieldToAppend);

        if (!$itemsCount) {
            $itemsCount = 1;
            if ($isMultipleInputs) {
                //TODO подумать как обойти этот костыль
                $this->model->{$this->attribute} = [[]];
                if (in_array('sku', $this->inputName) &&
                    !isset($this->model->{$this->attribute}[0]['sku'])) {
                    $this->model->{$this->attribute} = [
                        [
                            'sku' => $this->model->sku.'-1'
                        ]
                    ];
                }
            }

        }

        $errors = $this->model->getErrors($this->attribute);
        if (!empty($errors)) {
            $error = isset($errors[0]) ? $errors[0] : '';
        } else {
            $error = '';
        }

        $this->registerClientScript();

        echo $this->render(
            $isMultipleInputs ? 'multiple' : 'single',
            [
                'fieldToAppend' => $this->fieldToAppend,
                'fieldToAppendClass' => $fieldToAppendClass,
                'error' => $error,
                'attribute' => $this->attribute,
                'itemName' => $this->itemName,
                'itemsCount' => $itemsCount,
                'sortable' => $this->sortable,
                'model' => $this->model,
                'itemToCountClass' => $itemToCountClass,
                'inputName' => $this->inputName,
                'itemToCount' => $this->itemToCount,
                'maxRowsCount' => $this->maxRowsCount
           ]
        );
    }

    public function validateConfig()
    {
        if (!$this->model) {
            throw new InvalidConfigException('Нужно указать свойство "model"');
        }
        if (!$this->attribute) {
            throw new InvalidConfigException('Нужно указать свойство "attribute"');
        }
        if (!$this->fieldToAppend) {
            throw new InvalidConfigException('Нужно указать свойство "fieldToAppend"');
        }
        if (!$this->itemToCount) {
            throw new InvalidConfigException('Нужно указать свойство "itemToCount"');
        }
        if (!$this->itemName) {
            throw new InvalidConfigException('Нужно указать свойство "itemName"');
        }
        if (!$this->inputName) {
            throw new InvalidConfigException('Нужно указать свойство "inputName"');
        }
        if (is_array($this->inputName)) {
            if (empty($this->inputName)) {
                throw new InvalidConfigException('Нужно указать свойство "inputName"');
            }
            if (!is_array($this->itemName) ||
                empty($this->itemName) ||
                count($this->inputName) != count($this->itemName)
            ) {
                throw new InvalidConfigException('Укажите названия полей "itemName" в виде массива одинакового размера с ""inputName"');
            }
        }
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        CloneableInputAsset::register($view);

        if ($this->sortable) {
            CloneableInputSortableAsset::register($view);
            $view->registerJs('initSortable("'.$this->fieldToAppend.'")');
        }
    }
}
