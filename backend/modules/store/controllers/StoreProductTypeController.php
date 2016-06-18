<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductType;

/**
 * Class StoreProductTypeController
 * @package backend\modules\store\controllers
 */
class StoreProductTypeController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductType::className();
    }
}
