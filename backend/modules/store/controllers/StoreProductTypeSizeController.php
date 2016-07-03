<?php

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductTypeSize;

/**
 * Class StoreProductTypeSizeController
 * @package backend\modules\store\controllers
 */
class StoreProductTypeSizeController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductTypeSize::className();
    }
}
