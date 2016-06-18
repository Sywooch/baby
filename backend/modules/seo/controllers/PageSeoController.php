<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\seo\controllers;

use backend\controllers\BackendController;
use backend\modules\seo\models\PageSeo;

/**
 * Class PageSeoController
 * @package backend\modules\seo\controllers
 */
class PageSeoController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return PageSeo::className();
    }
}
