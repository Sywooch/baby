<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components\paymentSystems\interkassa;

use frontend\components\paymentAbstractFactory\IPaymentForm;
use yii\base\Model;

/**
 * Class InterkassaPaymentForm
 *
 * @package product\components\paymentSystems\interkassa
 */
class InterkassaPaymentForm extends Model implements IPaymentForm
{

    /**
     * ID магазина
     *
     * @var string
     */
    public $ik_co_id;

    /**
     * Сумма платежа. Дробная часть отделяется точкой
     *
     * @var float
     */
    public $ik_am;

    /**
     *ID платежа
     *
     * @var string
     */
    public $ik_pm_no;

    /**
     * Описание платежа
     *
     * @var string
     */
    public $ik_desc;

    /**
     * Способ оплаты для покупателя. Можен быть пустым
     *
     * @var string
     */
    public $ik_cur = 'UAH';

    /**
     * Цифровая подпись
     */
    public $ik_sign;

    /**
     * @inheritdoc
     */
    public function getApiUrl()
    {
        return 'https://sci.interkassa.com/';
    }

    public function generateSignature()
    {
        $dataSet = $this->getAttributes();
        $key = \Yii::$app->config->get('interkassa_secret_key');//secret_key;

        unset($dataSet['ik_sign']); //удаляем из данных строку подписи
        ksort($dataSet, SORT_STRING); // сортируем по ключам в алфавитном порядке элементы массива
        array_push($dataSet, $key); // добавляем в конец массива "секретный ключ"
        $signString = implode(':', $dataSet); // конкатенируем значения через символ ":"
        $sign = base64_encode(
            md5($signString, true)
        ); // берем sha256 хэш в бинарном виде по сформированной строке и кодируем в BASE64

        $this->ik_sign = $sign;
    }
}
