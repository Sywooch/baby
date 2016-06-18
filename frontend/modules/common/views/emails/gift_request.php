<?php
/**
 * Author: Pavel Naumenko
 *
 * @var \common\models\GiftRequest $model
 */
use yii\helpers\Html;

?>

<?php
echo Html::tag('p', 'Новый запрос на подбор подарка. Номер заявки #' . $model->id);

echo Html::tag('p', Html::tag('strong', 'Информация о запросе:'));


echo Html::tag('p', 'Имя: ' . $model->name);
echo Html::tag('p', 'Телефон: ' . $model->phone);
echo Html::tag('p', 'Пол: ' . \common\models\GiftRequest::getSex($model->sex));
echo Html::tag('p', 'Email: ' . $model->email);
echo Html::tag('p', 'Бюджет: ' . \common\models\GiftRequest::getBudget($model->gift_budget));
echo Html::tag('p', 'Повод для подарка: ' .$model->gift_reason);
echo Html::tag('p', 'Про подарок: ' . $model->about_gift);
echo Html::tag('p', 'Про получателя: ' . $model->about_receiver);
echo Html::tag('p', 'Кому подарок: ' . $model->receiver);
