<?php

namespace app\modules\store\models;

use frontend\components\FrontModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_order}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $nova_poshta_storage
 * @property string $discount_card
 * @property string $promo_code
 * @property string $comment
 * @property float $sum
 * @property integer $payment_type
 * @property integer $delivery_type
 * @property integer $delivery_time
 * @property integer $status
 * @property string $created
 * @property string $modified
 *
 * @property StoreOrderProduct[] $storeOrderProducts
 */
class StoreOrder extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['comment'], 'string'],
            [['payment_type', 'delivery_type', 'delivery_time', 'status'], 'integer'],
            [['created', 'modified'], 'safe'],
            [
                [
                    'name',
                    'phone',
                    'email',
                    'city',
                    'street',
                    'house',
                    'apartment',
                    'nova_poshta_storage',
                    'discount_card',
                    'promo_code'
                ],
                'string',
                'max' => 255
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя, фамилия',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'email' => 'Email',
            'street' => 'Улица',
            'house' => 'Дом',
            'apartment' => 'Квартира',
            'nova_poshta_storage' => 'Склад новой почты',
            'discount_card' => 'Скидочная карта',
            'promo_code' => 'Промо-код',
            'comment' => 'Комментарий',
            'payment_type' => 'Тип оплаты',
            'delivery_type' => 'Тип доставки',
            'delivery_time' => 'Время доставки',
            'status' => 'Cтатус',
            'recalculateTotalPrice' => 'Пересчитать сумму заказа',
            'created' => 'Создано',
            'modified' => 'Обновлено',
            'sum' => 'Стоимость',
            'payment_status' => 'Оплачен'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //\common\models\StoreOrder::fireNewRequestEvent();

        //$message = Yii::t('cart', 'Thank your for the order №{n}.', ['n' => $this->id]);

        //\Yii::$app->turbosms->send($message, $this->phone);
    }
}
