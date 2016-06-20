<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700',
        'http://fonts.googleapis.com/css?family=Gochi+Hand',
        'css/jquery.fancybox.css',
        'css/jquery.fancybox-thumbs.css',
        'css/stylesheet.css',
        'css/mobile.css',
        'css/cloud-zoom.css',
        'css/carousel.css',
        'css/frontend.css',
    ];
    public $js = [
        'js/jquery.jcarousel.min.js',
        'js/jquery.cycle.all.js',
        'js/jquery.selectBox.js',
        'js/cloud-zoom.1.0.2.js',
        'js/jquery.colorbox-min.js',
        'js/jquery.fancybox.pack.js',
        'js/cuties.js',
        'js/head.js',
        'js/frontend.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\LtIE9Asset',
    ];
}
