<?php

namespace app\modules\store\models;

use common\models\Currency;
use common\models\EntityToFile;
use common\models\Language;
use frontend\components\FrontModel;
use Yii;
use yii\db\Query;
use yii\helpers\Html;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

/**
 * This is the model class for table "{{%store_product}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $category_id
 * @property string $label
 * @property string $alias
 * @property string $announce
 * @property string $content
 * @property string $sku
 * @property string $price
 * @property integer $visible
 * @property integer $position
 * @property string $video_id
 * @property string $created
 * @property string $modified
 *
 */
class StoreProductCartPosition extends FrontModel implements CartPositionInterface
{
    use CartPositionTrait;

    public $variant;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return md5(serialize([$this->id, $this->variant]));
    }

    /**
     * @inheritdoc
     */
    public function getPrice()
    {
        $variant = $this->getVariant();
        $price = $variant ? $variant['price'] : $this->price;

        return Currency::getPriceInCurrency($price);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        $label = $this->label;

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
    public static function tableName()
    {
        return '{{%store_product}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainImage()
    {
        return $this->hasOne(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->where('entity_model_name = :emn', [':emn' => 'StoreProduct'])
            ->joinWith('file')
            ->orderBy('position DESC');
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
    public static function getShowCartUrl($params = [])
    {
        return self::createUrl('/store/cart/show-cart', $params);
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
}
