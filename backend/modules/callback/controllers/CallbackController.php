<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\callback\controllers;

use backend\controllers\BackendController;
use backend\modules\callback\models\Callback;

/**
 * Class CallbackController
 * @package backend\modules\callback\controllers
 */
class CallbackController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Callback::className();
    }
}
