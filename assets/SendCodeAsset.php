<?php
namespace app\assets;

use yii\web\AssetBundle;

class SendCodeAsset extends AssetBundle {

    public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/registration.css',              
    ];
    public $js = [
        'js/send-code.js',
    
    ];
    public $depends = [
    ];   
}
