<?php

namespace frontend\widgets\slider;

use app\modules\banner\models\Banner;


/**
 * Class Widget
 *
 * @package frontend\widgets\mainMenu
 */
class Widget extends \yii\base\Widget
{
    public function run()
    {
        $banners = Banner::find()->where(['visible' => 1])->all();
        if (!$banners) {
            return false;
        }
        
        return $this->render('default', ['banners' => $banners]);
    }
}
