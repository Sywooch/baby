<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\sales\controllers;

use backend\controllers\BackendController;
use backend\modules\sales\models\Sales;

/**
 * Class SalesController
 * @package backend\modules\sales\controllers
 */
class SalesController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Sales::className();
    }
}
