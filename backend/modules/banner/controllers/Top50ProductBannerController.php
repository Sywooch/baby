<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\controllers;

use backend\controllers\BackendController;
use backend\modules\banner\models\NewProductBanner;
use backend\modules\banner\models\Top50ProductBanner;

/**
 * Class Top50ProductBannerController
 * @package app\modules\banner\controllers
 */
class Top50ProductBannerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Top50ProductBanner::className();
    }
}
