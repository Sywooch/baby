<?php
/**
 * @var \app\modules\store\models\StoreOrder $order
 * @var \app\modules\store\models\StoreOrderProduct[] $orderItems
 * @var \app\modules\store\models\StoreOrderProduct $orderItem
 */
use app\modules\store\models\StoreProduct;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php
echo Html::tag('p', 'Новый заказ на сайте #' . $order->id);

echo Html::beginTag('table', ['style' => 'border: 1px solid black; width:100%; border-collapse:collapse;']);
echo Html::beginTag('thead');
echo Html::beginTag('tr');
echo Html::tag('td', 'Артикул', ['style' => 'border: 1px solid black;']);
echo Html::tag('td', 'Название', ['style' => 'border: 1px solid black;']);
echo Html::tag('td', 'Кол-во, шт', ['style' => 'border: 1px solid black;']);
echo Html::tag('td', 'Цена, грн.', ['style' => 'border: 1px solid black;']);
echo Html::tag('td', 'Общая цена, грн.', ['style' => 'border: 1px solid black;']);
echo Html::endTag('tr');
echo Html::endTag('thead');
foreach ($orderItems as $orderItem) {

    $price = $orderItem->product->getPrice();
    $label = Html::a(
        $orderItem->product->label,
        Url::to(
            StoreProduct::getProductUrl(['alias' => $orderItem->product->alias]),
            true
        )
    );

    echo Html::beginTag('tr');
    echo Html::tag('td', $orderItem->sku, ['style' => 'border: 1px solid black;']);
    echo Html::tag('td', $label, ['style' => 'border: 1px solid black;']);
    echo Html::tag('td', $orderItem->qnt, ['style' => 'border: 1px solid black;']);
    echo Html::tag('td', $price, ['style' => 'border: 1px solid black;']);
    echo Html::tag('td', $price * $orderItem->qnt, ['style' => 'border: 1px solid black;']);
    echo Html::endTag('tr');
}
echo Html::endTag('table');
echo Html::tag('p', 'Всего: ' . $order->sum. ' грн.');
echo Html::tag('p', Html::tag('strong', 'Информация о заказе:'));


echo Html::tag('p', 'Имя: ' . $order->name);
echo Html::tag('p', 'Телефон: ' . $order->phone);
echo Html::tag('p', 'Email: ' . $order->email);
echo Html::tag('p', 'Оплата: ' . \common\models\StoreOrder::getPaymentType($order->payment_type));
echo Html::tag('p', 'Доставка: ' . \common\models\StoreOrder::getDeliveryType($order->delivery_type));
echo Html::tag('p', 'Улица: ' . $order->street);
echo Html::tag('p', 'Дом: ' . $order->house);
echo Html::tag('p', 'Квартира: ' . $order->apartment);
echo Html::tag('p', 'Склад Новой почты: ' . $order->nova_poshta_storage);
echo Html::tag('p', 'Комментарий: ' . $order->comment);
