<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\CartVideo;

/**
 * Class CartVideoController
 * @package backend\modules\common\controllers
 */
class CartVideoController extends BackendController
{
    /**
     * @return string
     */
    public function getModel()
    {
        return CartVideo::className();
    }
}
