<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductMustHave;

/**
 * Class StoreProductMustHaveController
 * @package backend\modules\store\controllers
 */
class StoreProductMustHaveController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductMustHave::className();
    }
}
