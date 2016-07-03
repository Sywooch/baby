<?php

namespace backend\modules\store\models;

use kartik\select2\Select2;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * This is the model class for table "{{%store_order}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $street
 * @property string $address
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
 * @property integer $payment_status
 * @property string $created
 * @property string $modified
 *
 * @property StoreOrderProduct[] $storeOrderProducts
 */
class StoreOrder extends \backend\components\BackModel
{
    /**
     * @var array
     */
    public $products;

    /**
     * @var int
     */
    public $recalculateTotalPrice = 0;

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
            [['payment_type', 'payment_status', 'delivery_type', 'delivery_time', 'recalculateTotalPrice', 'status'], 'integer'],
            [['created', 'modified', 'sum', 'products'], 'safe'],
            [['sum'], 'default', 'value' => 0.00],
            ['email', 'email'],
            [['name', 'address', 'phone', 'email', 'street', 'house', 'apartment', 'nova_poshta_storage', 'discount_card', 'promo_code'], 'string', 'max' => 255],
            [['id', 'payment_status', 'name', 'sum', 'phone', 'email', 'street', 'house', 'apartment', 'nova_poshta_storage', 'promo_code', 'comment', 'payment_type', 'delivery_type', 'delivery_time', 'status', 'created', 'modified'], 'safe', 'on' => 'search']
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

    public function afterFind()
    {
        parent::afterFind();

        $this->products = (new Query())
            ->select(['id', 'product_id', 'cert_id', 'sku', 'qnt'])
            ->from(StoreOrderProduct::tableName())
            ->indexBy('id')
            ->all();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     *
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $insert
            ? \common\models\StoreOrder::createOrderInRetailCrm($this->id)
            : \common\models\StoreOrder::updateOrderInRetailCrm($this->id);
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        $this->saveProducts();

        if ($this->recalculateTotalPrice) {
            $this->recalculateTotalPrice();
        }

        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreOrderProducts()
    {
        return $this->hasMany(StoreOrderProduct::className(), ['order_id' => 'id']);
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
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'name', $this->name]);
            $query->andFilterWhere(['like', 'sum', $this->sum]);
            $query->andFilterWhere(['like', 'phone', $this->phone]);
            $query->andFilterWhere(['like', 'email', $this->email]);
            $query->andFilterWhere(['like', 'street', $this->street]);
            $query->andFilterWhere(['like', 'house', $this->house]);
            $query->andFilterWhere(['like', 'apartment', $this->apartment]);
            $query->andFilterWhere(['like', 'nova_poshta_storage', $this->nova_poshta_storage]);
            $query->andFilterWhere(['like', 'discount_card', $this->discount_card]);
            $query->andFilterWhere(['like', 'promo_code', $this->promo_code]);
            $query->andFilterWhere(['comment' => $this->comment]);
            $query->andFilterWhere(['payment_type' => $this->payment_type]);
            $query->andFilterWhere(['payment_status' => $this->payment_status]);
            $query->andFilterWhere(['delivery_type' => $this->delivery_type]);
            $query->andFilterWhere(['delivery_time' => $this->delivery_time]);
            $query->andFilterWhere(['status' => $this->status]);
            $query->andFilterWhere(['created' => $this->created]);
            $query->andFilterWhere(['modified' => $this->modified]);

        return $dataProvider;
    }

    /**
    * @param bool $viewAction
    *
    * @return array
    */
    public function getViewColumns($viewAction = false)
    {
        return $viewAction
            ? [
                'id',
                'name',
                'phone',
                'email',
                'address',
               /* 'house',
                'apartment',*/
                'nova_poshta_storage',
                //'discount_card',
                //'promo_code',
                'comment',
                'payment_type',
                'payment_status:boolean',
                'delivery_type',
                //'delivery_time',
                'status',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'name',
                'phone',
                'email',
                'sum',
                'created',
                [
                    'attribute' => 'status',
                    'value' => function (self $data) {
                            return \common\models\StoreOrder::getStatus($data->status);
                        }
                ],
                [
                    'attribute' => 'payment_status',
                    'filter' => static::getPaymentStatusList(),
                    'value' => function (self $data) {
                            return $data->payment_status ? 'Да' : 'Нет';
                        }
                ],
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'template' => '{view} {update} {delete} {print}',
                    'buttons' => [
                        'print' => function ($url) {
                            return Html::a(
                                Html::tag('span', null, ['class' => 'glyphicon glyphicon-print']),
                                $url,
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ]
                            );
                        }
                    ]
                ]
            ];
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            'form-set' => [
                'Основные' => [
                    'name' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    'phone' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    'email' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    'address' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    /*'street' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    'house' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    'apartment' => [
                        'type' => Form::INPUT_TEXT,
                    ],*/
                    'nova_poshta_storage' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    /*'discount_card' => [
                        'type' => Form::INPUT_TEXT,
                    ],*/
//            'promo_code' => [
//                'type' => Form::INPUT_TEXT,
//            ],
                    'comment' => [
                        'type' => Form::INPUT_TEXTAREA,
                        'options' => ['rows' => 5]
                    ],
                    'payment_type' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => \common\models\StoreOrder::getPaymentTypeList()
                    ],
                    'delivery_type' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => \common\models\StoreOrder::getDeliveryTypeList()
                    ],
                   /* 'delivery_time' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => \common\models\StoreOrder::getDeliveryTimeList()
                    ],*/
                    'status' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => \common\models\StoreOrder::getStatusList()
                    ],
                    'sum' => [
                        'type' => Form::INPUT_TEXT,
                    ],
                    'payment_status' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => static::getPaymentStatusList()
                    ],
                    'created' => [
                        'type' => Form::INPUT_STATIC,
                    ],
                    'modified' => [
                        'type' => Form::INPUT_STATIC,
                    ],
                ],
                'Позиции заказа' => [
                    /*'recalculateTotalPrice' => [
                        'type' => Form::INPUT_CHECKBOX,
                        'hint' => 'Пересчитать сумму заказа на основании суммы цен позиций заказа. Если вы хотите ввести произвольную сумму - снимите эту галочку.'
                    ],*/
                    'items' => [
                        'type' => Form::INPUT_RAW,
                        'value' => function (self $data) {
                            return $data->getItemsGrid()/*. $data->getAddProductInput()*/;
                        }
                    ]
                ]
            ]


        ];
    }

    public function recalculateTotalPrice()
    {
        $totalSum = 0.00;
        foreach ($this->products as $orderProductId => $productData) {

            $orderProduct = StoreOrderProduct::findOne($orderProductId);
            if ($orderProduct) {
                $totalSum += $orderProduct->size->price * $orderProduct->qnt;
            }

        }

        $this->sum = $totalSum;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function saveProducts()
    {
        if (!empty($this->products)) {
            foreach ($this->products as $orderProductId => $productData) {

                $data = isset($productData['sku'])
                    ?  [
                        'sku' => $productData['sku'],
                        'qnt' => $productData['qnt']
                    ]
                    :  [
                        'qnt' => $productData['qnt']
                    ];

                \Yii::$app->db->createCommand()
                    ->update(
                        StoreOrderProduct::tableName(),
                        $data,
                        ['id' => $orderProductId]
                    )->execute();
            }
        }
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 1;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'Заказы';
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getProductListUrl($params = [])
    {
        return static::createUrl('/store/store-order/get-product-list', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getAddProductToOrderUrl($params = [])
    {
        return static::createUrl('/store/store-order/add-product', $params);
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCourierDelivery()
    {
        $output = '';
        $freeDeliveryPrice = Yii::$app->config->get('free_delivery_price');
        $courierDeliveryPrice = Yii::$app->config->get('courier_deliver_price');
        $courierDeliveryPrice = $courierDeliveryPrice ? $courierDeliveryPrice : 35;


        if ($freeDeliveryPrice &&
            $this->sum < $freeDeliveryPrice &&
            $this->delivery_type == \common\models\StoreOrder::DELIVERY_TYPE_COURIER) {
            $output .= Html::beginTag('tr');

            $output .= Html::beginTag('td');
            $output .= 'Доставка';
            $output .= Html::endTag('td');
            $output .= Html::beginTag('td');
            $output .= 1;
            $output .= Html::endTag('td');
            $output .= Html::beginTag('td');
            $output .= $courierDeliveryPrice;
            $output .= Html::endTag('td');
            $output .= Html::beginTag('td');
            $output .= $courierDeliveryPrice;
            $output .= Html::endTag('td');

            $output .= Html::endTag('tr');
        }

        return $output;
    }

    /**
     * @return float|int
     */
    public function getTotalSum()
    {
        $sum = $this->sum;
        $freeDeliveryPrice = Yii::$app->config->get('free_delivery_price');
        $courierDeliveryPrice = Yii::$app->config->get('courier_deliver_price');
        $courierDeliveryPrice = $courierDeliveryPrice ? $courierDeliveryPrice : 35;

        if ($freeDeliveryPrice &&
            $this->sum < $freeDeliveryPrice &&
            $this->delivery_type == \common\models\StoreOrder::DELIVERY_TYPE_COURIER) {
            $sum += $courierDeliveryPrice;
        }

        return number_format($sum, 2, '.', '');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getItemsGrid()
    {
        $model = new StoreOrderProduct();

        return GridView::widget(
            [
                'layout' => '{items}',
                'dataProvider' => new ActiveDataProvider(
                    [
                        'query' => $model::find()->where(['order_id' => $this->id]),
                        'sort' => false
                    ]
                ),
                'filterModel' => false,
                'columns' => $model->getViewColumns(),
            ]
        );

    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getAddProductInput()
    {
        if ($this->isNewRecord) {
            return null;
        }

        return Html::tag(
            'div',
            Select2::widget(
                [
                    'name' => 'product_list',
                    'pluginOptions' => [
                        'allowClear' => true,
                        'ajax' => [
                            'url' => static::getProductListUrl(),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                            'results' => new JsExpression(
                                'function(data,page) {console.log(data.results); return {results:data.results}; }'
                            ),
                        ],

                    ],
                    'pluginEvents' => [
                        "change" => "function(e) {
                console.log(e);
                       addProductAfterSelect2It(e, '" . static::getAddProductToOrderUrl() . "', " . $this->id . ");
                    }",
                    ],
                    'options' => [
                        'placeholder' => 'Выберите товар'
                    ]
                ]
            )
        );

    }

    /**
     * @return array
     */
    public static function getPaymentStatusList()
    {
        return [
            0 => 'Нет',
            1 => 'Да'
        ];
    }

}
