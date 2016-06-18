<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\controllers;

use backend\controllers\BackendController;
use backend\modules\banner\models\BlogBanner;

/**
 * Class BlogBannerController
 * @package backend\modules\banner\controllers
 */
class BlogBannerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return BlogBanner::className();
    }
}
