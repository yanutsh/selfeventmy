<?

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?

foreach ($links as $link)
{
    echo Html::ul($links, [
        'class'  => 'social-links',
        'encode' => false,
    ]);
}