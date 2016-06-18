<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\FooterLink;

/**
 * Class FooterLinksController
 * @package backend\modules\common\controllers
 */
class FooterLinksController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return FooterLink::className();
    }
}
