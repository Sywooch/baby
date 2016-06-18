<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\assets;

use yii\helpers\Html;
use yii\web\AssetBundle;

/**
 * Class LtIE9Asset
 *
 * @package frontend\assets
 */
class LtIE9Asset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $js = [
        'themes/basic/js/html5shiv.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
        'condition' => 'lte IE9'
    ];
}
