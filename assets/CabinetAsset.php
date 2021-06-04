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
        'css/highslide.css',        
        'css/chosen.min.css', 
        'css/slick.css', 
        'css/slick-theme.css',
        'css/cabinet.css',
        'css/star_rating.css',
        
    ];
    public $js = [
        //'js/bootstrap.3.4.min.js',
        'js/highslide-with-gallery.js',        
        'js/chosen.jquery.js',
        'js/slick.min.js',
        'js/cabinet.js',
        'js/star_rating.js',
        
    ];
    public $depends = [
        'app\assets\AppAsset',
//        'rmrevin\yii\fontawesome\AssetBundle',
    ];   

}
