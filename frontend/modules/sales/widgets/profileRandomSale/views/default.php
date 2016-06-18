<?php
/**
 * Author: Pavel Naumenko
 *
 * @var Sales $model
 */
use frontend\modules\sales\models\Sales;
use yii\helpers\Html;

?>
<?php
$saleContent = '';

if (!empty($model->label)) {
    $saleContent .= Html::tag('span', $model->label, ['class' => 'btn-yellow-wrapper-strong']);
}

$saleContent .= Html::tag('span', $model->description);
if (!empty($model->content)) {
    $saleContent .= Html::tag('i', Yii::t('frontend', 'get more'), ['class' => 'more']);
}
$saleContent = Html::a($saleContent, Sales::getViewUrl(['alias' => $model->alias]), ['class' => 'btn-bunner-yellow']);

echo Html::tag('div', $saleContent, ['class' => 'btn-bunner-yellow-w']);
