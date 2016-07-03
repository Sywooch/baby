<?php

namespace backend\modules\store\models;

use backend\modules\common\models\Certificate;
use common\models\Currency as CurrencyCommon;
use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%store_order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $cert_id
 * @property string $sku
 *
 * @property StoreOrder $order
 * @property StoreProduct $product
 * @property StoreProductSize $size
 */
class StoreOrderProduct extends \backend\components\BackModel
{
    public $price;

    public $totalPrice;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_order_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'sku'], 'required'],
            [['order_id', 'product_id'], 'integer'],
            [['sku'], 'string', 'max' => 255],
            [['id', 'order_id', 'product_id', 'sku'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Продукт',
            'size_id' => 'Размер',
            'sku' => 'Артикул товара',
            'price' => 'Цена',
            'qnt' => 'Кол-во',
            'totalPrice' => 'Всего'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(StoreOrder::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(StoreProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(StoreProductSize::className(), ['id' => 'size_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCert()
    {
        return $this->hasOne(Certificate::className(), ['id' => 'cert_id']);
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
            'sort' => false
        ]);

        if (!empty($params)) {
            $this->load($params);
        }


            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['order_id' => $this->order_id]);
            $query->andFilterWhere(['product_id' => $this->product_id]);
            $query->andFilterWhere(['like', 'sku', $this->sku]);
    
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
                'product_id',
                'sku',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'label' => 'Изображение',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return $data->getImage();
                    }
                ],
                [
                    'attribute' => 'product_id',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return Html::a($data->getProductLabel(), static::getUrl(['id' => $data->product_id]));
                    }
                ],
                [
                    'attribute' => 'sku',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return $data->getSku();
                    }
                ],
                [
                    'attribute' => 'size_id',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return $data->size->size->getLabel();
                    }
                ],
                [
                    'attribute' => 'price',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return Html::tag('span', $data->size->price, ['class' => 'order-product-price']);
                    }
                ],
                [
                    'attribute' => 'qnt',
                    'format' => 'text',
                    'value' => function (self $data) {
                        return $data->qnt;
                    },
                    'options' => [
                        'class' => 'col-sm-1'
                    ]
                ],
                [
                    'attribute' => 'totalPrice',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return Html::tag(
                            'span',
                            number_format($data->size->price * ($data->qnt ? $data->qnt : 1), 2, '.', ''),
                            ['class' => 'order-product-total']);
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'controller' => 'store-order-product',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                Html::tag('span', null, ['class' => 'glyphicon glyphicon-trash']),
                                $url,
                                [
                                    'class' => 'ajax-link',
                                ]
                            );
                        }
                    ]
                ]


            ];
    }

    /**
     * @return float
     */
    public function getProductWithVariantPriceInCurrency()
    {
        return CurrencyCommon::getPriceInCurrency($this->getProductWithVariant()->price);
    }

    /**
     * @return array|StoreProduct|null|\yii\db\ActiveRecord
     */
    protected function getProductWithVariant()
    {
        $product = $this->product;

        if ($this->sku != $product->sku) {
            //This is variant
            $variant = StoreProductVariant::find()
                ->where(['product_id' => $product->id])
                ->andWhere(['sku' => $this->sku])
                ->one();

            if ($variant) {
                $product = $variant;
            }
        }

        return $product;
    }

    /**
     * @return string
     */
    public function getProductWithVariantLabel()
    {
        $product = $this->product;

        if ($this->sku != $product->sku) {
            //This is variant
            $variant = (new Query())
                ->from(StoreProductVariant::tableName())
                ->where(['product_id' => $product->id])
                ->andWhere(['sku' => $this->sku])
                ->one();

            if ($variant) {
                return $product->label .'('.$variant['label'] .')';
            }
        }

        return $product->label;
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            'product_id' => [
                'type' => Form::INPUT_TEXT,
            ],
            'sku' => [
                'type' => Form::INPUT_TEXT,
            ],

        ];
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 2;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'StoreOrderProduct';
    }

    /**
     * @return null|string
     */
    public function getImage()
    {
        if ($this->product_id) {
            $mainImage = $this->product->mainImage;
            return $mainImage
                ? FPM::image($this->product->mainImage->file_id, 'product', 'smallPreview')
                : null;
        } elseif ($this->cert_id) {
            $cert = $this->cert;

            if ($cert) {
                return Html::tag('span', CurrencyCommon::getPriceInCurrency($cert->price), [
                        'class' => $cert->color == \common\models\Certificate::COLOR_YELLOW
                            ? 'cert-span'
                            : 'cert-span cert-span-purple'
                ]);
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getProductLabel()
    {
        if ($this->product_id) {
            return $this->getProductWithVariantLabel();
        } elseif ($this->cert_id) {
            return $this->sku;
        }

        return null;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        $price = 0.00;

        if ($this->product_id) {
            $price = $this->getProductWithVariantPriceInCurrency();
        } elseif ($this->cert_id) {
            $cert = $this->cert;

            if ($cert) {
                $price = CurrencyCommon::getPriceInCurrency($cert->price);
            }
        }

        return $price;
    }

    /**
     * @return string
     */
    public function getQnt()
    {
        return Html::activeTextInput(
            $this->order,
            'products[' . $this->id . '][qnt]',
            ['class' => 'form-control order-product-qnt']
        );
    }

    /**
     * @return string
     */
    public function getSku()
    {
        if ($this->cert_id) {
            return Html::tag(
                'span',
                $this->sku,
                [
                    'class' => 'order-product-sku-static',
                    'data-price' => CurrencyCommon::getPriceInCurrency($this->cert->price)
                ]
            );
        }

        $product = $this->product;
        $order = $this->order;

        $variants = StoreProductVariant::findAll(
            ['product_id' => $this->product_id]
        );

        if (!empty($variants)) {
            $variantsList = [$product->sku => "$product->sku(Оригинал)"];
            $variantsOptions = [$product->sku => ['data-price' =>CurrencyCommon::getPriceInCurrency($product->price)]];

            foreach ($variants as $variant) {
                $variantsList[$variant->sku] = $variant->sku . '(' . $variant->label . ')';
                $variantsOptions[$variant->sku] = ['data-price' => CurrencyCommon::getPriceInCurrency($variant->price)];
            }

            return Html::activeDropDownList(
                $order,
                'products[' . $this->id . '][sku]',
                $variantsList,
                [
                    'options' => $variantsOptions,
                    'class' => 'form-control order-product-sku'
                ]
            );
        }

        return Html::tag(
            'span',
            $this->sku,
            [
                'class' => 'order-product-sku-static',
                'data-price' => CurrencyCommon::getPriceInCurrency($this->product->price)
            ]
        );
    }

    /**
     * @param array $params
     * @return string
     */
    public static function getUrl($params = [])
    {
        return static::createUrl('/store/store-product/update', $params);
    }
}
