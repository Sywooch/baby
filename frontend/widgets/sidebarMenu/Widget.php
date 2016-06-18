<?php

namespace frontend\widgets\sidebarMenu;

use app\modules\store\models\StoreCategory;

/**
 * Class Widget
 *
 * @package frontend\widgets\mainMenu
 */
class Widget extends \yii\base\Widget
{
    public function run()
    {
        $categories = StoreCategory::getParentCategories();
        
        return $this->render('default', ['categories' => $categories]);
    }
}
