<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\banner\controllers;

use backend\controllers\BackendController;
use backend\modules\banner\models\CategoryBanner;

/**
 * Class CategoryBanner
 * @package app\modules\banner\controllers
 */
class CategoryBannerController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return CategoryBanner::className();
    }
}
