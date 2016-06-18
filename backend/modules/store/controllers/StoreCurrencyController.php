<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\Currency;

/**
 * Class StoreCurrencyController
 * @package backend\modules\store\controllers
 */
class StoreCurrencyController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Currency::className();
    }
}
