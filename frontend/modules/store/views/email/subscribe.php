<?php
/**
 * Author: Pavel Naumenko
 *
 * @var integer $id
 * @var string $alias
 * @var string $name
 * @var string $email
 * @var string $phone
 */
use app\modules\store\models\StoreProduct;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php
$link = Html::a('Ссылка', Url::to(StoreProduct::getProductUrl(['alias' => $alias]), true));
echo Html::tag('p', 'Новый запрос на наличие товара. Номер заявки #' . $id);

echo Html::tag('p', Html::tag('strong', 'Интересующий товар: '). $link);

echo Html::tag('p', 'Имя: ' . $name);

echo Html::tag('p', 'Email: ' . $email);

echo Html::tag('p', 'Телефон: ' . $phone);
