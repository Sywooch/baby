<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\export\widgets\exportStatus;

use yii\web\AssetBundle;

/**
 * Class ExportStatusAsset
 * @package backend\modules\export\widgets\exportStatus
 */
class ExportStatusAsset extends AssetBundle
{
    public $js = [
        'ng_ctrls.js',
    ];

    public $depends = [
        'backend\modules\export\widgets\exportStatus\AngularAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js';

        parent::init();
    }
}
