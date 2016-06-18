<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\Certificate;

/**
 * Class CertificateController
 * @package backend\modules\common\controllers
 */
class CertificateController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Certificate::className();
    }
}
