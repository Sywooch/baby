<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\cloneableInput;

use yii\web\AssetBundle;

/**
 * Class JqueryUiAsset
 * @package backend\assets
 */
class CloneableInputSortableAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';

    public $js = [
        'jquery-ui.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
