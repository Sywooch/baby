<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\SeoFooterLinks;

/**
 * Class SeoFooterLinksController
 *
 * @package backend\modules\common\controllers
 */
class SeoFooterLinksController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return SeoFooterLinks::className();
    }
}
