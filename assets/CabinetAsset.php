<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CabinetAsset extends AssetBundle {

    //public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/cabinet.css',        
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/cabinet.js',        
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\AppAsset',
//        'rmrevin\yii\fontawesome\AssetBundle',
    ];   

}
