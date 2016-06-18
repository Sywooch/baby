<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\controllers;

use backend\controllers\BackendController;
use backend\modules\banner\models\NewProductBanner;

/**
 * Class NewProductBannerController
 * @package app\modules\banner\controllers
 */
class NewProductBannerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return NewProductBanner::className();
    }
}
