<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class SortableAsset
 *
 * @package backend\assets
 */
class SortableAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';

    public $js = [
        'jquery-ui.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
