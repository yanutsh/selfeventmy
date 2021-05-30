<?php
namespace app\assets;

use yii\web\AssetBundle;

class CanvasAsset extends AssetBundle {

    //public $sourcePath = '@app/assets';  // исходный вариант
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [        
        'css/app.css',              
    ];
    public $js = [        
        'js/app.js',          
    ];
    public $depends = [
    ];
}
