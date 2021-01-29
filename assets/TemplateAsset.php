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
class TemplateAsset extends AssetBundle {

    public $sourcePath = '@app/assets';  // исходный вариант
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $css = [
        'css/style.css',        
    ];
    public $js = [
        'js/script.js',
        //'js/fonts.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\AppAsset',
//        'rmrevin\yii\fontawesome\AssetBundle',
    ];

    public static function register($view) {
        $bundle = parent::register($view);
        $template = pathinfo($view->getViewFile(), PATHINFO_FILENAME);

        //debug($template);

        if (file_exists(\Yii::getAlias('@app/assets/css/templates/template-' . $template . '.css')))
            $bundle->css[] = 'css/templates/template-' . $template . '.css';
        if (file_exists(\Yii::getAlias('@app/assets/js/templates/template-' . $template . '.js')))
            $bundle->js[] = 'js/templates/template-' . $template . '.js';

        //debug($bundle);
        return $bundle;
    }

}
