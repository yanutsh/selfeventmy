<?php
namespace app\assets;

use yii\web\AssetBundle;

class RegistrationAsset extends AssetBundle {

    //public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/cabinet.css',
        'css/avatar.css', 
        'ui/jquery-ui.css', 
        'css/app.css',
        'css/slick.css', 
        'css/chosen.min.css',            
    ];
    public $js = [
        'js/registration.js', 
        'ui/jquery-ui.js',
        'js/jquery.ui.touch-punch.min.js', 
        'js/chosen.jquery.js',
        'js/cabinet.js',
        //'js/app.js',
        'js/slick.min.js',      
    ];
    public $depends = [
    ];

    //public $jsOptions = ['position' => \yii\web\View::POS_HEAD];   
}
