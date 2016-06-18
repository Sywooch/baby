<?php

namespace frontend\widgets\footerMenu;

use app\modules\store\models\StoreCategory;

/**
 * Class Widget
 *
 * @package frontend\widgets\mainMenu
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var bool
     */
    public $mobile = false;
    
    public function run()
    {
        $categories = StoreCategory::getParentCategories();
        if ($this->mobile) {
            $view = 'default_mobile';
        } else {
            $view = 'default';
        }
        
        return $this->render($view, ['categories' => $categories]);
    }
}
