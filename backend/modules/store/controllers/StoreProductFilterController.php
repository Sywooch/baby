<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductFilter;

/**
 * Class StoreProductFilterController
 * @package backend\modules\store\controllers
 */
class StoreProductFilterController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductFilter::className();
    }
}
