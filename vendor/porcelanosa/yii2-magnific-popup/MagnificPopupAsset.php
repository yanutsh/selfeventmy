<?php

namespace porcelanosa\magnificPopup;

class MagnificPopupAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/porcelanosa/yii2-magnific-popup/assets';
    
    public $js = [
        'js/jquery.magnific-popup.js',
    ];
    
    public $css = [
        'css/magnific-popup.css'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
