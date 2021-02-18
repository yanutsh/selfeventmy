<?php
//use Yii;
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\assets\CabinetAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
CabinetAsset::register($this);

?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<h1>Customer/index</h1>


	<?php 
		$identity = Yii::$app->user->identity;
		//debug ($identity['avatar']);
		$avatar = $identity['avatar'];		
	?>
	<img src="/web/uploads/images/users/<?= $avatar ?>" alt="Аватар">
	<h2>   Привет <?= $identity->username ?>!  Это твой ЛК</h2>

	<?php echo "  Isexec=".$identity->isexec."<br>"; ?>
    <?php echo "<br>"; ?> 
    <code><?= __FILE__; ?></code>.

     

	<div class="container">
		<div class="wrapper">
	    	<div class="row">
		    	<div class="col-md-4">
		    		<div class="lk__left">
		    			<a type='button' href="<?php echo Url::to(['customer/create-order', 'isexec' => '1']);?>" class='register active'>Создать заказ</a>
		    		</div>
		    	</div>
		    	<div class="col-md-8">
		    		<div class="lk__main">
		    			<p> 9 колонки </p>
		    		<div>	
		    	</div>

	    	</div>
	    </div>

	</div>    

