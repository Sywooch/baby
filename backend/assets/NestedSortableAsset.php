<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class NestedSortableAsset
 * @package backend\assets
 */
class NestedSortableAsset extends AssetBundle
{
    public $sourcePath = '@vendor/mjsarfatti/nestedSortable';
    public $js = [
        'jquery.mjs.nestedSortable.js',
    ];
    public $depends = [
        'backend\assets\SortableAsset',
    ];
}
