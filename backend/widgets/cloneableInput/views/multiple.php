<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $fieldToAppendClass
 * @var $error
 * @var $itemName []
 * @var $attribute
 * @var $itemsCount
 * @var $sortable
 * @var $inputName []
 * @var $itemToCount
 * @var $fieldToAppend
 * @var $maxRowsCount
 */
use \yii\helpers\Html;

$itemToWorkWith = $itemToCount; //for js handle
$itemToWorkWithClass = $itemToCountClass; //for js handle
$mainTagClass = 'inline-inputs row ' . $fieldToAppendClass;
$mainTagClass = $error ? ($mainTagClass . ' has-error') : $mainTagClass;

echo Html::beginTag('div', ['class' => $mainTagClass]);

$inputsCount = count($inputName) - 1;
$bootstrapWidthClass = floor(12 / ($inputsCount + 1));
$iterator = 1;
foreach ($model->{$attribute} as $i => $attr) {
    echo Html::beginTag(
        'div',
        [
            'class' => $sortable
                    ? 'cloneable cloneable-fixed-height sortable-item'
                    : 'cloneable cloneable-fixed-height'
        ]
    );
    $id = Html::getInputId($model, $attribute) . '_' . ($i - 1);
    $inputArrNo = $i;
    foreach ($itemName as $j => $iN) {

        echo Html::beginTag('div', ['class' => 'col-sm-'.$bootstrapWidthClass]);
        echo Html::beginTag('div', ['class' => 'form-group']);
        echo Html::label($iN, $id . '_' . $j, ['class' => 'control-label']);
        echo Html::beginTag('div', ['class' => ($j > 0 && $j < $inputsCount) ? '' : 'input-group']);

        if (!$j && $sortable) {
            echo Html::tag(
                'span',
                Html::a('<i class="glyphicon glyphicon-move"></i>', '#'),
                ['class' => 'input-group-addon']
            );
        }

        $classForVariantSku = '';
        $readonly = false;
        if ($inputName[$j] == 'sku') {
            $classForVariantSku = 'variant-sku';
            $readonly = true;
        }

        echo Html::activeTextInput(
            $model,
            $attribute . '[' . $inputArrNo . '][' . $inputName[$j] . ']',
            [
                'class' => 'form-control '.$itemToWorkWithClass. ' '. $classForVariantSku,
                'id' => $id . '_' . $j,
                'value' => isset($model->{$attribute}[$inputArrNo][$inputName[$j]])
                        ? $model->{$attribute}[$inputArrNo][$inputName[$j]]
                        : '',
                'data-item-to-count' => '.cloneable',
                'data-item-to-work-with' => $itemToWorkWith,
                'data-field-to-append' => $fieldToAppend,
                'data-max-rows' => $maxRowsCount,
                'readonly' => $readonly
            ]
        );


        if ($j == $inputsCount) {
            if ($iterator > 1) {
                echo Html::tag(
                    'span',
                    Html::a(
                        '<i class="glyphicon glyphicon-minus"></i>',
                        '#',
                        ['class' => 'cloneable-item-minus-multiple']
                    ),
                    ['class' => 'input-group-addon']
                );
            } else {
                echo Html::tag(
                    'span',
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        '#',
                        ['class' => 'cloneable-item-plus-multiple']
                    ),
                    ['class' => 'input-group-addon']
                );
            }
        }


        echo Html::endTag('div');

        echo Html::endTag('div');

        echo Html::endTag('div');
    }
    if ($error && $iterator == 1) {
        echo Html::tag('div', $error, ['class' => 'col-md-offset-1 help-block']);
    }
    echo Html::endTag('div');
    $iterator++;
}
echo Html::endTag('div');
