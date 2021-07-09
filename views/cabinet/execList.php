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
use yii\widgets\Pjax;

TemplateAsset::register($this);
//CabinetAsset::register($this);

?>

	<?php 
		$identity = Yii::$app->user->identity;
		//debug ($identity['avatar']);
		$avatar = $identity['avatar'];
		//debug($identity['isexec']);		
	?>
	
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
				    				<a href="/cabinet/executive-list" id="reset" alt="">Сбросить все</a>
			    				</div>			    				

			    				<?php  //Pjax::begin(); ?>
			    				<!-- Ajax запрос при изменении любого поля	 -->
			    				<?php $form = ActiveForm::begin([
			    					'id' => 'exec-filter-form', // по изменению этого id
				                    'options' => [
				                    	'data-pjax' => true,
				                     ],
				                    'action' => '/cabinet/executive-list',
								    'method'=>'post',
								    'enableAjaxValidation' => false,
				                ]); ?>

					                <div class="only_top">
				    					<div>Только ТОП 100</div>
				    					<div class="toggle-button-cover"> 
						                    <div class="button-cover">
						                        <div class="button r" id="button-top">
						                          <input type="checkbox" class="checkbox tuning" 
						                          	name= 'ExecFiltrForm[only_top]' 
							                      <?php if ($model['only_top']) echo 'checked';?>>
						                          <div class="knobs"></div>
						                          <div class="layer"></div>
						                        </div>
						                    </div>
						                </div>
				    				</div>

					                <div class="form-group input__block">
					                	<label class='control-label'>Рейтинг </label>
						                <select name="ExecFiltrForm[reyting]" id="execfiltrform-reyting" class="form-control">
						                	<option value='1'>По убыванию</option>
						                	<option value='0'>По возрастанию</option>          	
						                </select>
						                <!-- <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> -->
					                </div>

					                <div class="input__block">
						                <a href="#!" id="exec_reset_category">Cбросить</a>
						                <?= $form->field($model, 'category_id')->dropDownList (ArrayHelper::map($category, 'id', 'name'),['prompt'=>'Все категории']) ?>
					                </div>

					                <div class="input__block field-orderfiltrform-city_id">
					                	<a href="#!" id="exec_reset_city">Cбросить</a>
					                	<label class='control-label'>Город (города)</label>
						                <select name="ExecFiltrForm[city_id][]" id="execfiltrform-city_id" class="js-chosen city" multiple="multiple">
						                	<?php foreach($city as $c) {?>
						                		<option value=<?= $c['id']?>><?= $c['name']?> </option>
						                	<?php } ?>				                	
						                </select>
						                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
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

				                    <?= $form->field($model, 'work_form_id')->dropDownList (ArrayHelper::map($work_form, 'id', 'work_form_name'),['prompt'=>'Все формы работы']) ?>
				                    	
				                    <div class="form-group"> 
				                    	<div class='register__user active__button'>Показано исполнителей - <span><?= $count ?></span></div>
	                    			</div>

			                    <?php ActiveForm::end(); ?>
			                    <?php //Pjax::end(); ?>			                    

			    			</div>
			    		 </div>
			    	</div>

			    	<div class="col-md-8">
			    		<div class="lk__main">
			    			<div class="input-group order-search">
		    				  <span class="glyphicon glyphicon-search" aria-hidden="true"></span>	
							  <span class="input-group-addon search" id="basic-addon1"></span>
							  <input type="text" class="form-control" placeholder="Поиск" aria-describedby="basic-addon1"/>
							</div>
			    						    			
			    			<div id="exec_list" class="orders_list">
			    													    				
			    				<?php  	
			    				//debug($model);			    				
									echo $this->render('@app/views/partials/execlist.php', compact('exec_list', 'model','city', 'min_price'));
								?>	
			    			</div>
			    			
			    		</div>			    			
			    	</div>	
			   		
		    	</div>

	    	</div>
	    </div>

	</div>
	
