<?php
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
?>
<h1>user/index</h1>

<p>
	<br><br><br><br><br><br><br><br><br><br><br><br>
	
	<h2>   Привет <?= $user ?>!  Это твой ЛК</h2>

	<?php echo "  Isexec=".$_SESSION['isexec']."<br>"; ?>
    You may change the content of this page by modifying
    the file
    <?php echo "<br>"; ?> 
    <code><?= __FILE__; ?></code>.
</p>
