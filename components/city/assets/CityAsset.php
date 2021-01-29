<?

namespace app\components\city\assets;

use yii\web\AssetBundle;

class CityAsset extends AssetBundle
{

    public $sourcePath = '@app/components/city';
    public $css        = [
        'css/style.css',
    ];
    public $js         = [
        'js/script.js'
    ];
    public $depends    = [
        'yii\web\JqueryAsset',
        'yii\widgets\PjaxAsset',
    ];

}
