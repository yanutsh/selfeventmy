<?php
namespace app\assets;

use yii\web\AssetBundle;

class RegistrationAsset extends AssetBundle {

    public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/registration.css',              
    ];
    public $js = [
        'js/registration.js',
    
    ];
    public $depends = [
    ];   
}
