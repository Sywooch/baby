<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components\paymentSystems\interkassa;

use app\models\Payment;
use frontend\components\paymentAbstractFactory\IPaymentResultForm;
use yii\base\Model;

/**
 * Class InterkassaPaymentResultForm
 *
 * @package product\components\paymentSystems\interkassa
 */
class InterkassaPaymentResultForm extends Model implements IPaymentResultForm
{
    /**
     * ID магазина
     *
     * @var string
     */
    public $ik_co_id;

    /**
     * Сумма, которую заплатил пользователь. Дробь отделяется точкой
     *
     * @var float
     */
    public $ik_am;

    /**
     * Выбранный способ оплаты.
     *
     * @var string
     */
    public $ik_pw_via;

    /**
     * ID платежа(в системе продавца)
     *
     * @var unknown_type
     */
    public $ik_pm_no;

    /**
     * Описание покупки
     *
     * @var string
     */
    public $ik_desc;

    /**
     * Алиас платежной системы
     *
     * @var string
     */
    public $ik_cur;

    /**
     * Время выполнения платежа
     *
     * @var timestamp
     */
    public $ik_inv_prc;

    /**
     * Состояние платежа: "success" или "fail"
     *
     * @var string
     */
    public $ik_inv_st;

    /**
     * ID транзакции в системе интеркасса
     *
     * @var string
     */
    public $ik_trn_id;

    /**
     * Курс валюты магазина на момент создания платежа
     *
     * @var unknown_type
     */
    public $ik_ps_price;

    /**
     * электронная подпись
     *
     * @var string
     */
    public $ik_sign;

    /**
     * Идентификатор платежа.
     *
     */
    public $ik_inv_id;

    /**
     * Идентификатор кошелька кассы
     *
     */
    public $ik_co_prs_id;

    /**
     * Время создания платежа.
     *
     */
    public $ik_inv_crt;

    /**
     * Сумма зачисления на счет кассы.
     *
     */
    public $ik_co_rfn;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'ik_co_id',
                    'ik_am',
                    'ik_pm_no',
                    'ik_desc',
                    'ik_cur',
                    'ik_inv_prc',
                    'ik_inv_st',
                    'ik_ps_price',
                    'ik_sign'
                ],
                'required'
            ],
            [['ik_pw_via', 'ik_trn_id', 'ik_inv_id', 'ik_co_prs_id', 'ik_inv_crt', 'ik_co_rfn'], 'safe'],
            ['ik_pm_no', 'validatePaymentNo'],
            ['ik_sign', 'validateHash'],
        ];
    }

    /**
     * Проверяет ID операции на присутствие в БД
     *
     * @return boolean
     */
    public function validatePaymentNo()
    {
        $payment = Payment::find()
            ->andWhere(
                [
                    'status' => Payment::STATUS_WAIT_FOR_CONFIRM,
                    'id' => $this->ik_pm_no,
                    'sum_uah' => $this->ik_am
                ]
            )
            ->one();
        if (!$payment) {
            $this->addError('ik_pm_no', 'Платежная операция с таким ID не существует');

            return false;
        }

        return true;
    }

    /**
     * Проверяет совпадение котрольной подписи сервиса INTERKASSA по заданному
     * сервисом алгоритму
     *
     * @return boolean
     */

    public function validateHash()
    {
        if ($this->ik_pw_via == 'test_interkassa_test_xts') {
            $secretKey = \Yii::$app->config->get('interkassa_test_secret_key');
        } else {
            $secretKey = \Yii::$app->config->get('interkassa_secret_key');
        }

        $params = $this->getAttributes();

        unset($params['ik_sign']);

        ksort($params, SORT_STRING); // сортируем по ключам в алфавитном порядке элементы массива
        array_push($params, $secretKey); // добавляем в конец массива "секретный ключ"
        $signString = implode(':', $params); // конкатенируем значения через символ ":"
        $sign = base64_encode(md5($signString, true));


        if ($this->ik_sign != $sign) {
            $this->addError('ik_sign', 'Неверная подпись. Полученная-' . $this->ik_sign . '. Расчитанная -' . $sign);
        } elseif ($this->ik_inv_st == 'success') {
            return true;
        } else {
            $this->addError('ik_inv_st', 'Неверное состояние- ' . $this->ik_inv_st);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentId()
    {
        return $this->ik_pm_no;
    }
}
