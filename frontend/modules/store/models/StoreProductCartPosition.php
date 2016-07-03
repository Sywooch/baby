<?php

namespace app\modules\store\models;

use common\models\Currency;
use common\models\EntityToFile;
use common\models\Language;
use frontend\components\FrontModel;
use frontend\modules\store\models\StoreProductSize;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;


/**
 * Class StoreProductCartPosition
 *
 * @package app\modules\store\models
 */
class StoreProductCartPosition extends Model implements CartPositionInterface
{
    use CartPositionTrait;

    public $variant;

    /**
     * @var StoreProductSize
     */
    public $sizeId;

    /**
     * @var StoreProduct
     */
    public $productId;
    
    /** @inheritdoc */
    public function __construct($productId, $sizeId)
    {
        parent::__construct();
        
        $this->productId = $productId;
        $this->sizeId = $sizeId;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return md5(serialize([$this->productId, $this->sizeId]));
    }

    public function getPrice()
    {
        $price = (new Query())
            ->select(['t.price'])
            ->from(['t' => StoreProductSize::tableName()])
            ->where('t2.id = :PID AND t.id = :SID', [':PID' => $this->productId, ':SID' => $this->sizeId])
            ->join(
                'INNER JOIN',
                ['t2' => StoreProduct::tableName()],
                't.product_id = t2.id'
            )
            ->scalar();

        return $price;
    }

    /**
     * @return array|null|StoreProduct
     * @throws HttpException
     */
    public function getProduct()
    {
        $product = StoreProduct::find()
            ->from(['t' => StoreProduct::tableName()])
            ->where(['t.visible' => 1])
            ->andWhere(['t.status' => \common\models\StoreProduct::STATUS_AVAILABLE])
            ->andWhere('t.id = :id', [':id' => $this->productId])
            ->one();

        if (!$product) {
            throw new HttpException(500, 'Товар не найден');
        }

        return $product;
    }

    /**
     * @return null|StoreProductSize
     * @throws HttpException
     */
    public function getSize()
    {
        $size = StoreProductSize::findOne(['id' => $this->sizeId, 'product_id' => $this->productId]);

        if (!$size) {
            throw new HttpException(500, 'Размер не найден');
        }

        return $size;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        $label = $this->getProduct()->label;

        $currentLang = Language::getCurrent();
        if ($currentLang->code != Language::getDefaultLang()->code) {
            $translate = (new Query())
                ->from(StoreProductLang::tableName())
                ->where(['model_id' => $this->id])
                ->andWhere(['lang_id' => $currentLang->locale])
                ->one();

            if ($translate) {
                $label = $translate['label'];
            }
        }

        if ($this->variant) {
            $variant = $this->getVariant();
            if ($currentLang->code != Language::getDefaultLang()->code) {
                $translate = (new Query())
                    ->from(StoreProductVariantLang::tableName())
                    ->where(['model_id' => $variant['id']])
                    ->andWhere(['lang_id' => $currentLang->locale])
                    ->one();

                if ($translate) {
                    $label .= '('.$translate['label'].')';
                }
            } else {
                $label .= '('.$variant['label'].')';
            }
        }

        return $label;
    }

    /**
     * @inheritdoc
     */
    /*public static function tableName()
    {
        return '{{%store_product}}';
    }*/

    /**
     * @return EntityToFile
     */
    public function getMainImage()
    {
        return $this->getProduct()->mainImage;
    }

    /**
     * Add variant sku to cart position if it belong to current product
     *
     * @param $sku
     */
    public function setVariant($sku)
    {
        $doesVariantBelongToProduct = (new Query())
            ->from(StoreProductVariant::tableName())
            ->where('sku = :sku', [':sku' => $sku])
            ->andWhere(['product_id' => $this->id])
            ->one();

        if ($doesVariantBelongToProduct) {
            $this->variant = $sku;
        }
    }

    /**
     * @return array|bool|null
     */
    public function getVariant()
    {
        return $this->variant
            ? (new Query())
                ->from(StoreProductVariant::tableName())
                ->where('sku = :sku', [':sku' => $this->variant])
                ->andWhere(['product_id' => $this->id])
                ->one()
            : null;
    }

    /**
     * @param bool $all
     *
     * @return string
     */
    public function getRemoveUrl($all = false)
    {
        $params = [
            'id' => $this->id,
            'all' => $all
        ];

        if ($this->variant) {
            $params['sku'] = $this->variant;
        }

        return static::getCartRemoveUrl($params);
    }

    /**
     * @return string
     */
    public function getAddUrl()
    {
        $params = [
            'id' => $this->id,
        ];

        if ($this->variant) {
            $params['sku'] = $this->variant;
        }

        return static::getCartAddUrl($params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getSmallCartUrl($params = [])
    {
        return self::createUrl('/store/cart/get-small-cart', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCartAddUrl($params = [])
    {
        return self::createUrl('/store/cart/add', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCartRemoveUrl($params = [])
    {
        return self::createUrl('/store/cart/remove', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCartUpdateUrl($params = [])
    {
        return self::createUrl('/store/cart/update', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getShowCartUrl($params = [])
    {
        return self::createUrl('/store/cart/show-cart', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCheckoutUrl($params = [])
    {
        return self::createUrl('/store/cart/checkout', $params);
    }

    /**
     * @return string
     */
    public static function getShowCartButton()
    {
        $count = count(Yii::$app->cart->getPositions());

        if (!$count) {
            return Html::a(
                Html::tag('span', Yii::t('frontend', 'cart')),
                static::getSmallCartUrl(),
                [
                    'class' => 'btn-top-buscket btn-round btn-round__yell'
                ]
            );
        } else {
            return Html::a(
                Html::tag('i', '', ['class' => 'buscket']) . $count,
                static::getSmallCartUrl(),
                [
                    'class' => 'btn-top-buscket btn-round btn-round__yell'
                ]
            );
        }
    }

    /**
     * @param $route
     * @param $params
     *
     * @return string
     */
    public static function createUrl($route, $params)
    {
        return Url::to(ArrayHelper::merge(
            [$route],
            $params
        ));
    }
}
