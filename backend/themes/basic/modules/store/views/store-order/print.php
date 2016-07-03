<?php
/**
 * @var \backend\modules\store\models\StoreOrder $model
 */
use yii\helpers\Html;

?>

<?php echo Html::tag('h1', 'Заказ № ' . $model->id . ' (' . $model->created.')');

echo Html::beginTag('table');
echo Html::beginTag('thead');
echo Html::beginTag('tr');
echo Html::tag('td', 'Название');
echo Html::tag('td', 'Размер');
echo Html::tag('td', 'Кол-во, шт');
echo Html::tag('td', 'Цена, грн.');
echo Html::tag('td', 'Общая цена, грн.');
echo Html::endTag('tr');
echo Html::endTag('thead');
$orderItems = $model->storeOrderProducts;
foreach ($orderItems as $orderItem) {
    echo Html::beginTag('tr');
    echo Html::tag(
        'td',
        $orderItem->getProductLabel()
    );
    echo Html::tag('td', $orderItem->size->size->getLabel());
    echo Html::tag('td', $orderItem->qnt);
    echo Html::tag('td', $orderItem->size->price);
    echo Html::tag(
        'td',
        $orderItem->size->price * $orderItem->qnt
    );
    echo Html::endTag('tr');
}
echo $model->getCourierDelivery();
echo Html::endTag('table');
echo Html::tag('div', Html::tag('strong', 'Всего: ' . $model->getTotalSum() . ' грн.'), ['class' => 'right-text']);

echo Html::beginTag('ul', ['class' => 'left-text']);
echo Html::tag('li', Html::tag('strong', 'Информация о заказе:'));
echo Html::tag('li', 'Имя: ' . $model->name);
echo Html::tag('li', 'Телефон: ' . $model->phone);
echo Html::tag('li', 'Адрес: ' . $model->getFullAddress());
//echo Html::tag('li', 'Время доставки: ' . \common\models\StoreOrder::getDeliveryTime($model->delivery_time));
echo Html::tag('li', 'Склад Новой почты: ' . $model->nova_poshta_storage);
echo Html::endTag('ul');

echo Html::beginTag('ul', ['class' => 'left-text']);
echo Html::tag('li', 'Email: ' . $model->email);
echo Html::tag('li', 'Оплата: ' . \common\models\StoreOrder::getPaymentType($model->payment_type));
echo Html::tag('li', 'Доставка: ' . \common\models\StoreOrder::getDeliveryType($model->delivery_type));
echo Html::endTag('ul');

echo Html::tag('div', null, ['class' => 'clearfix']);
echo Html::tag('div', Html::tag('p', 'Комментарий: ' . $model->comment), ['class' => 'left-text']);
