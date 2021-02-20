<?php
//use Yii;
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\assets\CabinetAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
//CabinetAsset::register($this);

?>

	<?php 
		$identity = Yii::$app->user->identity;
		//debug ($identity['avatar']);
		$avatar = $identity['avatar'];		
	?>
	<!-- <h2>   Привет <?= $identity->username ?>!  Это твой ЛК</h2> -->

	<div class="container">
		<div class="wrapper">
	    	<div class="row">

				<div class="wrapper__lk">
			    	<div class="col-md-4">
			    		<div class="lk__left">
			    			<div class="filtr">
			    				<a type='button' href="<?php echo Url::to(['customer/create-order', 'isexec' => '1']);?>" class='register active'>Создать заказ</a>
			    			
			    				<div class="title">Фильтр</div>
			    			</div>
			    		</div>
			    	</div>
			    	<div class="col-md-8">
			    		<div class="lk__main">
			    			
			    		<div>	
		    	</div>

	    	</div>
	    </div>

	</div>
