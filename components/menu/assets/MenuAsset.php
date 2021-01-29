<?

namespace app\components\menu\assets;

use yii\web\AssetBundle;

class MenuAsset extends AssetBundle
{

    public $sourcePath = '@app/components/menu';
    public $css        = [
        'css/style.css',
    ];
    public $js         = [
        'js/script.js'
    ];
    public $depends    = [
//        'yii\web\JqueryAsset',
//        'yii\widgets\PjaxAsset',
    ];

}
