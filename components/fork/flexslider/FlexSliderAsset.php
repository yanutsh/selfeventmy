<?php

namespace app\components\fork\flexslider;

use yii\web\AssetBundle;

/**
 * Assets of FlexSlider.
 */
class FlexSliderAsset extends AssetBundle
{

    public $sourcePath = '@app/components/fork/flexslider/assets';
    public $css        = [
        'flexslider.css',
    ];
    public $js         = [
        'jquery.flexslider-min.js',
    ];
    public $depends    = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
