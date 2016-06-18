<?php
namespace app\modules\store\widgets\productsOnMainPage;

use app\modules\store\models\StoreProduct;


/**
 * Class Widget
 *
 * @package app\modules\store\widgets\storeCategoryMenu
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var string
     */
    public $type;

    /**
     * @inheritdoc
     */
    public function run()
    {
        switch ($this->type) {
            case StoreProduct::WIDGET_LATEST:
                $field = 'is_new';
                break;
            case StoreProduct::WIDGET_SALE:
                $field = 'is_sale';
                break;
            case StoreProduct::WIDGET_POPULAR:
                $field = 'is_popular';
                break;
            default:
                return false;
        }
        $products = StoreProduct::find()
            ->where([$field => 1, 'visible' => 1])
            ->joinWith('mainImage')
            ->limit(4)
            ->all();
        if (!$products) {
            return false;
        }

        return $this->render('default', ['products' => $products, 'title' => $this->type]);
    }
}
