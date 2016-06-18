<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\PayAndDelivery;

/**
 * Class PayAndDeliveryController
 * @package backend\modules\common\controllers
 */
class PayAndDeliveryController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return PayAndDelivery::className();
    }
}
