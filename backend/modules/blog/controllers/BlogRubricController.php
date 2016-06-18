<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\blog\controllers;

use backend\controllers\BackendController;
use backend\modules\blog\models\BlogRubric;

/**
 * Class BlogRubricController
 * @package backend\modules\blog\controllers
 */
class BlogRubricController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return BlogRubric::className();
    }
}
