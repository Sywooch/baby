<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\controllers;

use backend\controllers\BackendController;
use backend\modules\banner\models\MainPageBanner;

/**
 * Class MainPageBannerController
 * @package app\modules\banner\controllers
 */
class MainPageBannerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return MainPageBanner::className();
    }
}
