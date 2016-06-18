<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class GMapAsset
 *
 * @package frontend\assets
 */
class GMapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'https://maps.googleapis.com/maps/api/js?v=3.9&sensor=false'
    ];
}
