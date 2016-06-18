<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\export\widgets\exportStatus;

use yii\web\AssetBundle;

/**
 * Class AngularAsset
 * @package backend\modules\export\widgets\exportStatus
 */
class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower/angular';

    public $js = [
        'angular.min.js',
    ];
}
