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
	$avatar = $identity['avatar'];	
	$this->title = 'Личный кабинет';	?>	

<div class="container">

	<div class="wrapper">
    	<div class="row">

			<div class="wrapper__lk">
		    
		    	<div class="col-md-4">
		    		<div class="lk__left">
		    			<div class="filtr">
		    				<?php
		    				// показываем кнопку только Заказчикам  
		    				if ($identity['isexec'] == 0) { ?>
		    					<a type='button' href="<?php echo Url::to(['cabinet/add-order', 'isexec' => '1']);?>" class='register active'>Создать заказ</a>
		    				<?php } ?>

		    				<div class="filtr__header">
			    				<div class="title">Фильтр</div>
			    				<a href="/cabinet" id="reset" alt="">Сбросить</a>
		    				</div>
		    				<?php Pjax::begin(); ?>	
		    				<?php $form = ActiveForm::begin([
		    					'id' => 'order-filter-form',
			                    'options' => [
			                    	'data-pjax' => true,
			                     ],
			                    'action' => '/cabinet/index',
							    'method'=>'post',
							    'enableAjaxValidation' => false,
			                ]); ?>

			                <div class="input__block">
				                <a href="#!" id="order_reset_category">Cбросить</a>
				                <?= $form->field($model, 'category_id')->dropDownList (ArrayHelper::map($category, 'id', 'name'),['prompt'=>'Все категории']) ?>
				            </div> 
				            
			                <div class="input__block field-orderfiltrform-city_id">
			                	<a href="#!" id="order_reset_city">Cбросить</a>       	
			                	<label class='control-label'>Город (города)</label>
				                <select name="OrderFiltrForm[city_id][]" id="orderfiltrform-city_id" class="js-chosen city" multiple="multiple">
				                	<?php foreach($city as $c) {?>
				                		<option value=<?= $c['id']?>><?= $c['name']?> </option>
				                	<?php } ?>				                	
				                </select>
				                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
			                </div>

			                
			                <div class="form-group">
				                <label class='control-label'>Дата от</label>
				                <?php echo DatePicker::widget([                		
								    'name' => 'OrderFiltrForm[date_from]',
								    'type' => DatePicker::TYPE_COMPONENT_APPEND,
								    'removeButton' => false,
								    //'value' => date('d.m.Y', mktime(0, 0, 0, date("m")  , date("d")-365, date("Y"))),
								    'value' => Yii::$app->params['date_from'], 
								    'pluginOptions' => [
								        'autoclose'=>true,
								        'orientation' => 'center left',
		                                'todayHighlight' => true,
		                                'todayBtn' => true,				     
								    ]
								]); ?>
							</div>	
			               
							<div class="input__block">
			                    <label class='control-label'>Дата до</label>
				                <?php echo DatePicker::widget([
								    'name' => 'OrderFiltrForm[date_to]',
								    'type' => DatePicker::TYPE_COMPONENT_APPEND,
								    'removeButton' => false,
								    'value' => Yii::$app->params['date_to'],
								    'pluginOptions' => [
								        'autoclose'=>true,
								        'orientation' => 'center left',
		                                'todayHighlight' => true,
		                                'todayBtn' => true,				     
								    ]
								]); ?>
							</div>	
	                        
		                    <div class="input__block">
		                    	<?= $form->field($model, 'budget_from') ?>
		                    	<span class="glyphicon glyphicon-ruble" aria-hidden="true"></span>
		                    </div>

		                    <div class="input__block">
		                    	<?= $form->field($model, 'budget_to') ?>
		                   		 <span class="glyphicon glyphicon-ruble" aria-hidden="true"></span>
		                    </div>

		                    <?= $form->field($model, 'prepayment')->dropDownList (ArrayHelper::map($payment_form, 'id', 'payment_name'),['prompt'=>'Все формы оплаты']) ?>

		                    <div class="input__block">
		                    	<a href="#!" id="order_reset_work_form">Cбросить</a>
		                    	<?= $form->field($model, 'work_form_id')->dropDownList (ArrayHelper::map($work_form, 'id', 'work_form_name'),['prompt'=>'Все формы работы']) ?>
		                    </div>
		                 

		                    <?= $form->field($model, 'order_status_id')->dropDownList (ArrayHelper::map($order_status, 'id', 'name'),['prompt'=>'Любой статус заказа']) ?>
		                    	
		                    <div class="form-group"> 
		                    	<div class='register__user active__button'>Показано заказов - <span><?= $count ?></span> шт.</div>
                			</div>
		                    <?php ActiveForm::end(); ?>
		                    <?php Pjax::end(); ?>
		                   
		    			</div>
		    		 </div>
		    	</div>

		    	<div class="col-md-8">
		    		<div class="lk__main">
		    			  
		    			<!-- <p id='orders_list_header'></p> -->
		    			
		    			<div id="orders_list" class="orders_list">

		    				<div class="input-group order-search">
		    				  <span class="glyphicon glyphicon-search" aria-hidden="true"></span>	
							  <span class="input-group-addon search" id="basic-addon1"></span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
							</div>

		    				<!-- <div class="input__block">
			                    <div class="form-group">
									
									<input type="text" id="order-search" class="form-control" name="order-search" aria-invalid="false">

									<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
			                    </div>
		                	</div>   -->
		    				<?php
		    					require_once('../views/partials/orderslist.php');		    					
								//echo $this->render('@app/views/partials/orderslist.php', compact('orders_list', 'pages','orderResponseForm'));
							?>	
		    			</div>
		    			
		    		<div>			    			
		    	</div>	
		   		
	    	</div>

    	</div>
    </div>

</div>

