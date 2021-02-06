<?php
namespace app\assets;

use yii\web\AssetBundle;

class ConfirmCodeAsset extends AssetBundle {

    public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/registration.css',              
    ];
    public $js = [
        'js/confirm-code.js',
    
    ];
    public $depends = [
    ];   
}
