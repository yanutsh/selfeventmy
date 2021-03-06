<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\UserEducation;

use yii\bootstrap\Modal;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use yii\widgets\Pjax;
use kartik\date\DatePicker;

//use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

$identity = Yii::$app->user->identity;
?>
<div class="wrapper__addorder wrapper__addorder__card">
    
    <div class="order_content">
        <div class="notifications_content">
            <div class="order_content__title">Информация о профиле</div> 

        <!-- О Себе  -->    
            <?php Pjax::begin(); ?>
            <div class="order_content__subtitle">
            	<span>О себе</span>
            	<?php 
				// модальное окно - О Себе					
					Modal::begin([
					    'header' => '<h2>Редактирование информации</h2>',
					    'id' => "myself_win",	
					    'toggleButton' => [
					     	'label' => 'Редактировать',
					     	'tag' => "a",
					     	'class' => 'text_details profile',
					     	'id' => 'modal_myself',
					     ],					    
					]);

					$form = ActiveForm::begin([
						'id' => 'myself-form',
						'options' => [
	                        'data-pjax' => true,	                       
	                        ],
						]); ?>

						<input hidden type="text" name="field_name" value="myself">
						<?= $form->field($user, 'myself')->textarea(['style'=>'resize:vertical', 'rows'=>'5']); ?>
	    				
						<div class="form-group">
		        		 	<?= Html::submitButton('Сохранить', [
		        				'class' => 'register__user active__button save', 
		        				'name' => 'myself-button',
		        				'id'=> 'myself-button'
		        			]) ?>
		    			</div>
	 
	    			<?php ActiveForm::end(); ?>

	    			<?php 
					Modal::end();					
				// модальное окно - О Себе -Конец	
				?>	
            </div>
            <div class="text"><?= $user['myself'] ?></div>
            <?php
				$script = <<< JS
				    // Закрытие фона модального окна
				    $('#myself-button').click(function(e){
				   	    // отправка формы по pjax и потом удаление фона:   	   	
				    	$('.modal-backdrop.fade.in').css('display','none'); 
				    	$('body').removeAttr('class');  	    	
					});

				JS;
				//маркер конца строки, обязательно сразу, без пробелов и табуляции
				$this->registerJs($script, yii\web\View::POS_READY);
			?>
            <?php Pjax::end(); ?>
        <!-- О Себе -Конец	     -->
        
        <!-- Вывод данных только для Исполнителя ------------------------  -->
        <?php if ($identity['isexec']) {?>        	
        
        <!-- Города------------------------------------------------------  -->    
            <?php Pjax::begin(); ?>
            <?php //debug($user) ?>
            <div class="order_content__subtitle">
            	<span>Города</span>
            	<?php 
				// модальное окно - Города					
					Modal::begin([
					    'header' => '<h2>Редактирование информации</h2>',
					    'id' => "city_win",	
					    'toggleButton' => [
					     	'label' => 'Редактировать',
					     	'tag' => "a",
					     	'class' => 'text_details profile',
					     	'id' => 'modal_city',
					     ],					    
					]); ?>

					<!-- список введенных городов для удаления -->
					<?php Pjax::begin(['enablePushState' => false]); ?>
						<table class='user_cities'>
							<?php 							
							foreach($user['cities'] as $u_city) 
							{?>
								<tr>
									<td class="city_name"><?= $u_city['name'] ?></td>
									<td class="city_del">
										<a href="/cabinet/delete-user-city?city_id=<?= $u_city['id']?>">
											<img src="/web/uploads/images/delete_icon_32px.png" alt="">
										</a>								
									</td>
								</tr>
							<?php } ?>
						</table>				    
					<?php Pjax::end(); ?>
					
					<?php					
					// форма добавления городов
					$form = ActiveForm::begin([
						'id' => 'city-form',
						'options' => [
	                        'data-pjax' => true,	                       
	                        ],
						]); ?>

						<input hidden type="text" name="field_name" value="city">
						<?= $form->field($user_city, 'city_id[]')->dropDownList(ArrayHelper::map($city, 'id', 'name'),
							[	//'prompt'=>'Выберите город',
								'id' => "cityform-city_id",
								'class' => "js-chosen city",
								'multiple' => "multiple",
							]) 
							->label('Добавить город(а)') ?>
	    				
						<div class="form-group">
		        		 	<?= Html::submitButton('Сохранить изменения', [
		        				'class' => 'register__user active__button save', 
		        				'name' => 'city-button',
		        				'id'=> 'add-city-button'
		        			]) ?>
		    			</div>	 
	    			<?php ActiveForm::end(); ?>

	    			<?php 
					Modal::end();					
				// модальное окно - Города -Конец	
				?>		
            	
            </div>

            <div class="text"><?php 
            	$cities_all="";
            	foreach($user['cities'] as $city){
            		if ($cities_all  =='') $cities_all = $city['name'];
            		else $cities_all .= ", ".$city['name'];            		
            	}
            	echo $cities_all;
             ?>             	
            </div>
            <?php
				$script = <<< JS
				    // Закрытие фона модального окна
				    $('#add-city-button').click(function(e){
				   	    // отправка формы по pjax и потом удаление фона:   	   	
				    	$('.modal-backdrop.fade.in').css('display','none'); 
				    	$('body').removeAttr('class');  	    	
					});

					$('.js-chosen.city').chosen({
				        width: '100%',
				        no_results_text: 'Совпадений не найдено',
				        placeholder_text_single: 'Выберите город',
				        placeholder_text_multiple: 'Любой городд',
				    });
				JS;
				//маркер конца строки, обязательно сразу, без пробелов и табуляции
				$this->registerJs($script, yii\web\View::POS_READY);
			?>
            <?php Pjax::end(); ?>
        <!-- Города -Конец	---------------------------------------------- -->

        <!-- Категории услуг---------------------------------------------  -->
        	<?php //Pjax::begin();?>
            <div class="order_content__subtitle">
            	<span>Сфера деятельности</span>
            	<?php 
				// модальное окно - Редактировать Вид деятельности					
				Modal::begin([
					    'header' => '<h2>Редактирование деятельности</h2>',
					    'id' => "action_win",	
					    'toggleButton' => [
					     	'label' => 'Редактировать',
					     	'tag' => "a",
					     	'class' => 'text_details profile',
					     	'id' => 'modal_action',
					     ]
					]); ?>

					<!-- список введенных услуг для удаления -->
					<?php Pjax::begin(['enablePushState' => false]); ?>							
						<div class='subtitle'>Выбраны услуги:</div>
						<table class='user_cities'>
							<?php 
							//debug($user_category);
							foreach($user_subcategory as $u_cat) 
							{?>
								<tr>
									<td class="city_name"><?= $u_cat['subcategory']['name'] ?></td>
									<td class="city_del">
										<a href="/cabinet/delete-user-subcategory?subcategory_id=<?= $u_cat['subcategory']['id']?>">
											<img src="/web/uploads/images/delete_icon_32px.png" alt="">
										</a>								
									</td>
								</tr>
							<?php } ?>
						</table>
					<?php Pjax::end(); ?> 
					
					<!-- Форма ввода категорий -->
					<?php Pjax::begin(); ?>
					    <?php 
					    $form = ActiveForm::begin([
					                'id' => 'add-category-form',
					                'enableClientValidation' => false,
					                'options' => [
					                    'data-pjax' => true,                           
					                ],
					            ]); ?>				     

					        <!-- Категории -->
					        <input hidden type="text" name="field_name" value="category">
					        <div class='subtitle'>Добавление услуги:</div> 
					        <?= $form->field($user_category, 'category_id')->dropDownList(	
					            	ArrayHelper::map($category, 'id', 'name'),
					            	 [  'prompt'=>'Выберите категорию услуги',
					                    'id'=>'category_id',
					                 ])->label('') ?>
					        
					        <!-- Подкатегории          -->
					        <?php if (!empty($subcategory)) {?>

						        <?= $form->field($user_category, 'subcategory_id')->dropDownList(ArrayHelper::map($subcategory, 'id', 'name'),[
							            'prompt'=>'Выберите вид услуги',
							            'id'=>'subcategory_id',					            
							             ])
							            ->label(''); ?>

							    <div class="flex_block">
			                        <?= $form->field($user_category, 'price_from',['enableClientValidation' => true]) ?>
			                        <?= $form->field($user_category, 'price_to') ?>
			                    </div>

		                    	<div class="choose_elements price">	                        
			                        <!-- <div class="item_choose"> -->
			                            <div class="form-group exactly price">
			                                <div>
			                                    <label class="control-label">Указать точную сумму</label>
			                                </div>        
			                                <div class="toggle-button-cover"> 
			                                      <div class="button-cover">
			                                        <div class="button r" id="button-1">
			                                          <input type="checkbox" class="checkbox tuning" id="price_exactly">
			                                          <div class="knobs"></div>
			                                          <div class="layer"></div>
			                                        </div>
			                                      </div>
			                                </div>
			                            </div>

			                            <?= $form->field($user_category, 'price')->label(false) ?>
			                        <!-- </div>         -->
			                    </div>    
						    <?php } ?>

					        <div class="form-group">
					            <?= Html::submitButton('Сохранить', 
					            	['class' => 'register__user active__button save',
					            	 'name'=>'save_actions', 
					            	 'id'=>'save_actions',
					            	 'value'=>'true'
					            	]) ?>
					        </div>
						    <?php ActiveForm::end(); ?>
						    <?php
								$script = <<< JS
									$('#category_id').on('change',function(event){
										event.preventDefault(); 
								        // признак отправки формы НЕ для сохранения данных
								        $('#save_actions').val('false');              
								        $('#add-category-form').submit();			        
								    });

								    $('#save_actions').on('click', function(event){   	
								        // признак отправки формы ДЛЯ сохранения данных
								        $(this).val('true');      				        
								    })

								    // Переключатель показа поля точного бюджета)
								    $('#price_exactly').click(function(event) {
								    	//alert ("Показать");
								    	if ($("#price_exactly").prop("checked"))	$('#usercategory-price').show(1000);
								    	else {
								    		$('#usercategory-price').hide(1000);
								    		$('#usercategory-price').val(null);
								    		}
								    })
								JS;
			                        //маркер конца строки, обязательно сразу, без пробелов и табуляции
			                        $this->registerJs($script, yii\web\View::POS_READY);
						    ?>
				    <?php Pjax::end(); ?>
	    			<?php
	    		Modal::end();?>

	    	</div>				
            <!-- цикл по услугам список--> 
            <?php
            	//debug($user_subcategory);
            	            	
            	foreach($user_subcategory as $u_scat) { ?>
					<div class="text profile">
	            		<span><?=$u_scat['subcategory']['name'] ?></span>
	            		<div class="text_details profile"><span>от </span><?=$u_scat['price_from'] ?> Р</div>
	            	</div>					
				<?php 
				}  ?> 					      		       
	        <?php	        	 									
			// модальное окно - Вид Деятельности -Конец	
			?>			
			
			<?php //Pjax::end();	?>
		<!-- Категории услуг Конец---------------------------------------  -->        

		<!-- Образование ------------------------------------------------  -->
			<?php Pjax::begin([
		        'timeout' => false, 
		        'enablePushState' => true,
    		]); ?>
	        <div class="order_content__subtitle">
            	<span>Образование</span>            	
            </div>
            <!-- организовать цикл по Курсам обучения--> 
            <?php
            $i=1; 
            foreach($user_education as $ue) { 
            	$model[$i] = new UserEducation(); 
            	$model[$i]->id = $ue['id'];
            	$model[$i]->user_id = $ue['user_id'];
            	$model[$i]->institute = $ue['institute'];
            	$model[$i]->course = $ue['course'];
            	$model[$i]->start_date = $ue['start_date'];
            	$model[$i]->end_date = $ue['end_date'];
            	//debug($model,0);
            	//debug($ue,0);  ?>                    
	            <div class="text profile">
	            	<span><?= $ue['institute'] ?></span>
	            	<?php 
					//--модальное окно - Редактировать Образование					
					Modal::begin([
						    'header' => '<h2>Редактирование образования</h2>',
						    'id' => "education_win_".$i,	
						    'toggleButton' => [
						     	'label' => 'Редактировать',
						     	'tag' => "a",
						     	'class' => 'text_details profile',
						     	'id' => 'modal_education_'.$i,
						     ]
						]); ?>
										
						<!-- Форма редактирования Образования -->
						<?php //Pjax::begin(); ?>
						    <?php 
						    $form = ActiveForm::begin([
						                'id' => 'edit-education-form-'.$i,
						                //'enableClientValidation' => true,
						                'options' => [
						                    'data-pjax' => true,                           
						                ],
						            ]); ?>				     

						        <!-- Образование -->
						        <input hidden type="text" name="field_name" value="edit_education">

						       								        
						        <?= $form->field($model[$i], 'id')->hiddenInput()->label(false) ?>
						        <?= $form->field($model[$i], 'institute') ?>
						        <?= $form->field($model[$i], 'course') ?>
						        <?php   
						        	if(!is_null($model[$i]->start_date))
						        		$start_date = convert_date_en_ru($model[$i]->start_date);
						        	else $start_date = null;
						        	
						        	if(!is_null($model[$i]->end_date))
						        		$end_date = convert_date_en_ru($model[$i]->end_date);
						        	else $end_date = null;					        	
						        ?>
						        <div class="form-group">
		                            <label class="control-label">Даты обучения</label>'
		                            <?php 
		                            echo DatePicker::widget([
		                                'name' => 'UserEducation[start_date]',
		                                'value' => $start_date,                   
		                                'type' => DatePicker::TYPE_RANGE,
		                                'name2' => 'UserEducation[end_date]',
		                                'value2' => $end_date,  
		                                'pluginOptions' => [
		                                    'autoclose' => true,
		                                ]
		                            ]);
		                            ?>
		                        </div>
														        
						        <div class="form-group">
						            <?= Html::submitButton('Удалить', 
						            	['class' => 'register__user active__button save', 
						            	 'name'=>'delete_education', 
						            	 'id'=>'delete_education_'.$i,
						            	 'value'=>'true',
						            	]) ?>
						        </div>
						        <div class="form-group">
						            <?= Html::submitButton('Сохранить', 
						            	['class' => 'register__user active__button save', 
						            	 'name'=>'edit_education', 
						            	 'id'=>'edit_education_'.$i,
						            	 'value'=>'true'
						            	]) ?>
						        </div>
							<?php ActiveForm::end(); ?>
							    <?php
									$script = <<< JS
									// Закрытие фона модального окна
									$('#edit_education_$i, #delete_education_$i').click(function(e){			
										// отправка формы-pjax и удаление фона:
										$('.modal-backdrop.fade.in').css('display','none');
										$('body').removeAttr('class');  	    	
									});	
									JS;
									$this->registerJs($script, yii\web\View::POS_READY);
							    ?>
					    <?php //Pjax::end(); ?>
		    			<?php
		    		Modal::end();?>
				</div>

				<div class="text profile study">
	            	<span><?php if(!empty($ue['course'])) echo($ue['course']); else echo "<br>" ?></span>
	            	<div class="text_details profile dates">
	            		<?php if(!is_null($ue['start_date'])) echo('с '.convert_date_en_ru($ue['start_date'])) ?>
	            		<?php  if(!is_null($ue['end_date'])) echo(' по '.convert_date_en_ru($ue['end_date'])) ?>
	            	</div>            	
	            </div>
            <?php $i++;
        	} ?>           
	                             
	       	<?php 
			// модальное окно - Добавить Образование
				$model = new UserEducation();					
				Modal::begin([
				    'header' => '<h2>Добавление образования</h2>',
				    'id' => "add_education_win",	
				    'toggleButton' => [
				     	'label' => 'Добавить образование',
				     	'tag' => "a",
				     	'class' => 'text_details profile',
				     	'id' => 'modal_add_education',
				     ]
				]); ?>
								
				<!-- Форма ввода Образования -->
				<?php //Pjax::begin(); ?>
				    <?php 
				    $form = ActiveForm::begin([
				                'id' => 'add-education-form',
				                'enableClientValidation' => true,
				                'options' => [
				                    'data-pjax' => true,                           
				                ],
				            ]); ?>				     

				        <!-- Образование -->
				        <input hidden type="text" name="field_name" value="add_education">
				        				        
				        <?= $form->field($model, 'institute') ?>
				        <?= $form->field($model, 'course') ?>
				        
				        <div class="form-group">
                            <label class="control-label">Даты обучения</label>'
                            <?php 
                            echo DatePicker::widget([
                                'name' => 'UserEducation[start_date]',                 
                                'type' => DatePicker::TYPE_RANGE,
                                'name2' => 'UserEducation[end_date]',
                                'pluginOptions' => [
                                    'autoclose' => true,
                                ]
                            ]);
                            ?>
                        </div> 
				        
				        <div class="form-group">
				            <?= Html::submitButton('Сохранить', 
				            ['class' => 'register__user active__button save',
				             'name'=>'add_education', 
				             'id'=>'add_education',
				             'value'=>'true'
				            ]) ?>
				        </div>
					<?php ActiveForm::end(); ?>
				    <?php
						$script = <<< JS
							$('#add_education').on('click', function(){
								$('.modal-backdrop.fade.in').css('display','none');
								$('body').removeAttr('class');
							})  
						JS;
						$this->registerJs($script, yii\web\View::POS_READY);
				    ?>
		    	<?php //Pjax::end(); ?>
    			<?php
    			Modal::end();
    			?>
	    	<?php Pjax::end(); ?>
		<!-- Образование Конец-------------------------------------------  -->  

		<?php } ?>
		<!-- Вывод данных только для Исполнителя Конец-------------------  -->

		<!-- Контактные данные -->
	        <?php Pjax::begin(); ?>
	        <div class="order_content__subtitle">
            	<span>Контактные данные</span>
            	<?php 
				// модальное окно - Контактные данные					
					Modal::begin([
					    'header' => '<h2>Редактирование профиля</h2>',
					    'id' => "contact_win",					    
					    'toggleButton' => [
					    	'label' => 'Редактировать',
					    	'tag' => "a",
					    	'class' => 'text_details profile',
					    	'id' => 'modal_fio',					    
					    ],					   
					]);
            		
					$form = ActiveForm::begin([
						'id' => 'contact-form',
						'options' => [
	                        'data-pjax' => true,	                       
	                        ],
						]); ?>

						<input hidden type="text" name="field_name" value="contact">
						<?= $form->field($user, 'username') ?>
	    				
						<div class="form-group">
		        		<?= Html::submitButton('Сохранить', 
		        			['class' => 'register__user active__button save', 
		        			 'name' => 'contact-button',
		        			 'id'=> 'contact-button'
		        			]) ?>
		    			</div>
	 
	    			<?php ActiveForm::end(); ?>

	    			<?php 
					Modal::end();					
				// модальное окно - Контактные данные -Конец	
				?>	
            </div>
            <div class="text" id="test"><?= $user['workForm']['work_form_name']." ".$user['username'] ?></div>
			<?php
				$script = <<< JS
				    // Закрытие фона модального окна
				    $('#contact-button').click(function(e){
				   	    // отправка формы по pjax и потом удаление фона:   	   	
				    	$('.modal-backdrop.fade.in').css('display','none'); 
				    	$('body').removeAttr('class');  	    	
					});
				JS;
				//маркер конца строки, обязательно сразу, без пробелов и табуляции
				$this->registerJs($script, yii\web\View::POS_READY);
			?>
            <?php Pjax::end(); ?>
        <!-- Контактные данные Конец-->

        <!-- Редактирование документов -->
          	<?php if ($identity['isexec']) {?>

		        <div class="order_content__subtitle">
	            	<span>Редактирование документов</span>
	            	<a href="<?= Url::to('/doc/update')?>" class="text_details profile">Редактировать</a>
	            </div>

	            
		<!-- Редактирование документов  Конец-->

		<!-- Установка предоплаты Конец-->
				<div class="order_content__subtitle">              	
	            	<div class="form-group exactly">                    
		                <span class="prepayment"><?php if ($identity['isprepayment']) echo 'Работаю по предоплате'; else echo 'Работаю без предоплаты';?></span>                         
		                <div class="toggle-button-cover"> 
		                      <div class="button-cover">
		                        <div class="button r" id="button-1">
		                          <input type="checkbox" class="checkbox tuning" name= 'isprepayment' 
			                      <?php if ($identity['isprepayment']) echo 'checked';?>>
		                          <div class="knobs"></div>
		                          <div class="layer"></div>
		                        </div>
		                      </div>
		                </div>
	            	</div>  
            	</div>
            <?php 	
			$script = <<< JS
				$('input.checkbox.tuning').on('change', function(){					
					if ($('input.checkbox').is(':checked')){
						$('.prepayment').text('Работаю по предоплате.');
						status=1;
					} else {						
						$('.prepayment').text('Работаю без предоплаты.'); 
						status=0; 
					}
					$.ajax({
					  type: 'POST',
					  url: '/cabinet/set-prepayment?action=sample2',
					  data: 'status='+status,
					  success: function(data){
					    console.log(data);
					  }

					});
					
				})
			JS;
			//маркер конца строки, обязательно сразу, без пробелов и табуляции
			$this->registerJs($script, yii\web\View::POS_READY);
			?>	
        <!-- Установка предоплаты Конец-->

        	<?php } ?>    	
           	

        <!-- Удаление профиля-->
        	<?php Pjax::begin(); ?>
            <div class="order_content__subtitle">
            	<span>Удалить профиль</span>
            	<?php 
				// модальное окно - Контактные данные					
					Modal::begin([
					    'header' => '<h2>Удаление аккаунта</h2>',
					    'id' => "delete_win",					    
					    'toggleButton' => [
					    	'label' => 'Удалить',
					    	'tag' => "a",
					    	'class' => 'text_details profile',
					    	'id' => 'modal_delete',					    
					    ],
					    'footer' => false,
					]);
            		
					$form = ActiveForm::begin([
						'id' => 'delete-form-profile',
						'options' => [
	                        'data-pjax' => true,	                       
	                        ],
						]); ?>

						<input hidden type="text" name="field_name" value="delеte">
						<div class="delete_account">
							<h3>Вы действительно хотите удалить аккаунт?</h3>
							<p>Профиль будет полностью удален <br> без возможности его восстановить</p>
						</div>		    				
						<div class="form-group">
		        		<?= Html::submitButton('Удалить', [
		        				'class' => 'register__user active__button save', 
		        				'name' => 'delete-button',
		        				'id'=> 'delete-button'
		        			]) ?>
		    			</div>
	 
	    			<?php ActiveForm::end(); ?>

	    			<?php 
					Modal::end();					
				// модальное окно - Контактные данные -Конец	
				?>	
            </div>
            <div class="text">Навсегда удалить профиль.</div> 
            
            <?php
				$script = <<< JS
				    // Закрытие фона модального окна
				    $('#delete-button').click(function(e){
				   	    // отправка формы по pjax и потом удаление фона:   	   	
				    	$('.modal-backdrop.fade.in').css('display','none'); 
				    	$('body').removeAttr('class');
				    	//имитация клика по кнопке модального окна с последней инфо.
				    	$('#modal_deleteinfo').click();  	    	
					});
				JS;
				//маркер конца строки, обязательно сразу, без пробелов и табуляции
				$this->registerJs($script, yii\web\View::POS_READY);
			?>

            <?php Pjax::end(); ?>           
                      					
	        <?php Pjax::begin();
			// Скрытое модальное окно - Удаление - последнее инфо 					
				Modal::begin([
				    'header' => '<h2>Аккаунт Удален</h2>',
				    'id' => "deleteinfo_win",					    
				    'toggleButton' => [
				    	'label' => 'Предупреждение',
				    	'tag' => "a",
				    	'class' => 'text_details profile hidden',
				    	'id' => 'modal_deleteinfo',					    
				    ],
				    'footer' => false,
				]);
	    		
				$form = ActiveForm::begin([
					'id' => 'delete-form-last',
					'options' => [
	                    'data-pjax' => true,	                       
	                    ],
					]); ?>

					<input hidden type="text" name="field_name" value="deleteinfo">
					<div class="delete_account">
						<!-- <h3>Последнее предупреждение - Вы действительно хотите удалить аккаунт?</h3> -->
						<p>Аккаунт скрыт из сервиса.<br>В течении 30 дней его можно<br> восстановить - для этого зайдите в свой аккаунт.<br> В противном случае он будет удален безвозвратно!</p>
					</div>		    				
					<div class="form-group">
	        		<?= Html::submitButton('Вернуться на главную', [
	        				'class' => 'register__user active__button', 
	        				'name' => 'deleteinfo-button',
	        				'id'=> 'deleteinfo-button'
	        			]) ?>
	    			</div>

				<?php ActiveForm::end(); ?>

				<?php 
				Modal::end();					
			// модальное окно - Удаление последнее инфо -Конец 
			Pjax::end();	
			?>
		<!-- Удаление профиля Конец -->
		
			<div class="order_buttons">
	            <a href="<?= Url::to('/cabinet/user-tuning') ?>" class="register__user active__button save">В настройки</a>          
	        </div>          
        

		</div>
    </div>        
</div>




                   