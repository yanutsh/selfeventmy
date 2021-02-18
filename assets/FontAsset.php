<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FontAsset extends AssetBundle
{

//    public $sourcePath = '@app/assets';
    public $basePath   = '@webroot';
    public $baseUrl    = '@web';
    public $css        = [
//        '//fonts.googleapis.com/css?family=Roboto+Condensed:400,700&subset=cyrillic',
//        '//fonts.googleapis.com/css?family=Roboto:400,700&subset=cyrillic',
//        '//fonts.googleapis.com/css?family=Exo+2:400,700&subset=cyrillic',
//        '//fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:400,700&subset=cyrillic',
    ];
    public $cssOptions = [
        'type' => 'text/css',
        'rel'  => "stylesheet",
    ];
    public $js         = [
        '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js',
        'js/fonts.js',
    ];
    public $depends    = [
        'rmrevin\yii\fontawesome\AssetBundle',
    ];

}
