<?php
namespace frontend\widgets\mainMenu;

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
        $count = count($categories) - 1;
        
        return $this->render('default', ['categories' => $categories, 'count' => $count]);
    }
}
