<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\seo\controllers;

use backend\controllers\BackendController;
use backend\modules\seo\models\MetaTag;

/**
 * Class MetaTagController
 * @package backend\modules\seo\controllers
 */
class MetaTagController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return MetaTag::className();
    }
}
