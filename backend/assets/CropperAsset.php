<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class CropperAsset
 * @package backend\assets
 */
class CropperAsset extends AssetBundle
{
    public $sourcePath = '@bower/cropper/dist';

    public $js = [
        'cropper.min.js',
    ];

    public $css = [
        'cropper.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
