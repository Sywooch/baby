<?php

namespace backend\modules\staticPage\controllers;

use backend\controllers\BackendController;
use backend\modules\staticPage\models\StaticPage;

/**
 * Default controller for the `staticPage` module
 */
class DefaultController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StaticPage::className();
    }
}
