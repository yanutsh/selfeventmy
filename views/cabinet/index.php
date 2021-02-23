<?php
//use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\assets\CabinetAsset;
use kartik\date\DatePicker;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
//CabinetAsset::register($this);

?>

	<?php 
		$identity = Yii::$app->user->identity;
		//debug ($identity['avatar']);
		$avatar = $identity['avatar'];		
	?>
	
	<div class="container">

		<div class="wrapper">
	    	<div class="row">

				<div class="wrapper__lk">
			    	
			    	<div class="col-md-4">
			    		<div class="lk__left">
			    			<div class="filtr">
			    				<a type='button' href="<?php echo Url::to(['customer/create-order', 'isexec' => '1']);?>" class='register active'>Создать заказ</a>

			    				<?//= debug($category); ?>
			    			
			    				<div class="title">Фильтр</div>
			    				
			    				<?php $form = ActiveForm::begin([
			    					'id' => 'filter-form',
				                    //'options' => [
				                    //   'data-pjax' => true,
				                    //   ],
				                    'action' => '/cabinet/index',
								    'method'=>'post',
								    'enableAjaxValidation' => false,
				                ]); ?>

				                <?= $form->field($model, 'category_id')->dropDownList (ArrayHelper::map($category, 'id', 'name'),['prompt'=>'Все категории']) ?>
				                
				                <div class="input__block">
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
			                    	
			                    <div class="form-group"> 
			                    	<div class='register__user active__button'>Показать заказы - <span><?= $count ?></span> шт.</div>
                    			</div>
			                    <?php ActiveForm::end(); ?>
			                    <!-- Ответ сервера будем выводить сюда -->
								<p id="output"></p>

			    			</div>
			    		</div>
			    	</div>

			    	<div class="col-md-8">
			    		<div class="lk__main">

			    			<p>Список отфильтрованных заказов:</p>
			    			<p id='orders_list_header'></p>
			    			
			    			<div id="orders_list" class="orders_list">
			    				<div class="order_item">
				    				<div class="order_status">Исполнитель выбран</div>
				    				<div class="order_category">Ведущий</div>
				    				<div class="order_details">Описание заказа</div>
				    				<div class="order_budget">10000 <span class="rubl">₽</span></div>
				    				<div class="order_down">
				    					<div class="order_city">Москва</div>
				    					<div class="order_added">20 минут назад</div>
				    				</div>	
				    			</div>

				    			<div class="order_item">
				    				<div class="order_status">Исполнитель выбран</div>
				    				<div class="order_category">Ведущий</div>
				    				<div class="order_details">Описание заказа</div>
				    				<div class="order_budget">10000</div>	
				    				<div class="order_down">
				    					<div class="order_city">Москва</div>
				    					<div class="order_added">20 минут назад</div>
				    				</div>
				    			</div>	

				    			<div class="order_item">
				    				<div class="order_status">Исполнитель выбран</div>
				    				<div class="order_category">Ведущий</div>
				    				<div class="order_details">Описание заказа</div>
				    				<div class="order_budget">10000 </div>	
				    				<div class="order_down">
				    					<div class="order_city">Москва</div>
				    					<div class="order_added">20 минут назад</div>
				    				</div>
				    			</div>		
			    			</div>

			    		<div>			    			
			    	</div>		
		    	</div>

	    	</div>
	    </div>

	</div>
