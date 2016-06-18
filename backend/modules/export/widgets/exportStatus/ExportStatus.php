<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\export\widgets\exportStatus;

use yii\base\Widget;

/**
 * Class ExportStatus
 * @package backend\modules\export\widgets\exportStatus
 */
class ExportStatus extends Widget
{

    public function run()
    {
        ExportStatusAsset::register($this->getView());

        return $this->render('default');
    }
}
