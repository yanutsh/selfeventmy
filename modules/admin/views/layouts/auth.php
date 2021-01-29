<?php 
use app\assets\AuthAsset;
use yii\helpers\Html;

AuthAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <base href='/adminlte/'>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        
        <?php $this->head() ?>  
</head>
<?php $this->beginBody() ?>
<body class="hold-transition login-page">
	
	     <?= $content ?>

<?php $this->endBody() ?> 
</body>
</html>
<?php $this->endPage() ?>
