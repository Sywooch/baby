<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\cloneableInput;

use yii\web\AssetBundle;

/**
 * Class CloneableInputAsset
 * @package backend\widgets\cloneableInput
 */
class CloneableInputAsset extends AssetBundle
{
    public $js = [
        'cloneable-input.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__.DIRECTORY_SEPARATOR.'assets';

        parent::init();
    }
}
