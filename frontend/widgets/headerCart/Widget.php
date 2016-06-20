<?php

namespace frontend\widgets\headerCart;

use app\modules\store\models\StoreCategory;
use common\models\StaticPage;

/**
 * Class Widget
 *
 * @package frontend\widgets\mainMenu
 */
class Widget extends \yii\base\Widget
{
    public function run()
    {
        $pages = StaticPage::find()
            ->where(['visible' => 1])
            ->orderBy('position DESC')
            ->all();
        
        return $this->render('default', ['pages' => $pages]);
    }
}
