<?php
//use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\assets\CabinetAsset;
use kartik\date\DatePicker;
use app\components\page\PageAttributeWidget as PAW;
use yii\widgets\Pjax;


TemplateAsset::register($this);
//CabinetAsset::register($this);
RegistrationAsset::register($this);
?>

<?php 
	$identity = Yii::$app->user->identity;
?>	

<div class="container">

	<div class="wrapper">
    	<div class="row">

			<div class="wrapper__lk">	    	

		    	<div class="col-md-12">
		    		<div class="lk__main">
		    			  
		    			<p class='offer_header'>Кликните на заказе, который Вы хотите предложить исполнителю</p>
		    			
		    			<div id="orders_list" class="orders_list">		    				
		    				<?php
		    					require_once('../views/partials/orderslist_offer.php');	
							?>	
		    			</div>
		    			
		    		<div>			    			
		    	</div>	
		   		
	    	</div>

    	</div>
    </div>

</div>

