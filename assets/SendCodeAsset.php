<?php
namespace app\assets;

use yii\web\AssetBundle;

class SendCodeAsset extends AssetBundle {

    //public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/time-to.css',
        'css/send-code.css',             
    ];
    public $js = [
        'js/send-code.js',
        'js/jquery.time-to.min.js'
    
    ];
    public $depends = [
    ];   
}
