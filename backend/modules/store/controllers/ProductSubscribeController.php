<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductSubscribe;

/**
 * Class ProductSubscribeController
 * @package backend\modules\store\controllers
 */
class ProductSubscribeController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductSubscribe::className();
    }
}
