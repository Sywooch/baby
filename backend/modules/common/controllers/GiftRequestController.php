<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\GiftRequest;

/**
 * Class GiftRequestController
 * @package backend\modules\common\controllers
 */
class GiftRequestController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return GiftRequest::className();
    }
}
