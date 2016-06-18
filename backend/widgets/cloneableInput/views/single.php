<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $fieldToAppendClass
 * @var $error
 * @var $itemName
 * @var $attribute
 * @var $itemsCount
 * @var $sortable
 * @var $itemToCountClass
 * @var $inputName
 * @var $itemToCount
 * @var $fieldToAppend
 * @var $maxRowsCount
 */
use \yii\helpers\Html;

?>
<div class="form-group <?php echo $fieldToAppendClass ?> required  <?php echo $error ? ' has-error' : '' ?>">
    <label class="control-label" for="<?php echo $attribute ?>"><?php echo $itemName ?></label>
    <?php for ($i = 1; $i <= $itemsCount; $i++) { ?>
        <div class="input-group cloneable <?php echo $sortable ? 'sortable-item' : ''; ?>">
            <?php if ($sortable) { ?>
                <span class="input-group-addon">
                    <a href="#">
                        <i class="glyphicon glyphicon-move"></i>
                    </a>
                </span>
            <?php
            }

            echo Html::activeTextInput(
                $model,
                $attribute . '[]',
                [
                    'class' => 'form-control ' . $itemToCountClass,
                    'id' => Html::getInputId($model, $attribute) . '_' . ($i - 1),
                    'value' => isset($model->{$attribute}[$i-1]) ? $model->{$attribute}[$i-1] : '',
                    'data-name' => $inputName,
                    'data-item-to-count' => $itemToCount,
                    'data-field-to-append' => $fieldToAppend,
                    'data-max-rows' => $maxRowsCount
                ]
            );


            if ($i > 1) {
                ?>
                <span class="input-group-addon">
                    <a class="cloneable-item-minus" href="#">
                        <i class="glyphicon glyphicon-minus"></i>
                    </a>
                </span>
            <?php } else { ?>
                <span class="input-group-addon">
                    <a class="cloneable-item-plus" href="#">
                        <i class="glyphicon glyphicon-plus"></i>
                    </a>
                </span>

            <?php
            }
            ?>
        </div>
        <?php
        if ($error && $i == 1) {
            ?>
            <div class="help-block"><?php echo $error; ?></div>
        <?php
        }
    } ?>
</div>
