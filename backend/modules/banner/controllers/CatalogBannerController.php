<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\controllers;

use backend\controllers\BackendController;
use backend\modules\banner\models\CatalogBanner;

/**
 * Class CatalogBannerController
 * @package backend\modules\banner\controllers
 */
class CatalogBannerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return CatalogBanner::className();
    }
}
