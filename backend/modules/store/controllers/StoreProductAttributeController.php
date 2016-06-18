<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductAttribute;

/**
 * Class StoreProductAttributeController
 * @package backend\modules\store\controllers
 */
class StoreProductAttributeController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductAttribute::className();
    }
}
