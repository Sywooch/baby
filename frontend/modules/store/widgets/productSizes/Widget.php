<?php
namespace app\modules\store\widgets\productSizes;

use app\modules\store\models\StoreProduct;


/**
 * Class Widget
 *
 * @package app\modules\store\widgets\productSizes
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var StoreProduct
     */
    public $model;
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $sizes = $this->model->productSizes;
        if (empty($sizes)) {
            return false;
        }
        
        return $this->render('default', [
            'sizes' => $sizes
        ]);
    }
}
