<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\bootstrap\Modal;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use yii\widgets\Pjax;

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
					    'footer' => 'низ',
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
		        				'class' => 'register__user active__button', 
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
           
        <?php // Вывод данных только для Исполнителя ************************* **********************************************************************           
        //if ($identity['isexec']) {?>        	
        
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
					    'footer' => 'низ',
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
		        				'class' => 'register__user active__button', 
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
        	<?php //Pjax::begin(); ?>
            <div class="order_content__subtitle">
            	<span>Сфера деятельности</span>
            	<?php 
				// модальное окно - Добавить/Удалить Вид деятельности					
				Modal::begin([
					    'header' => '<h2>Добавление деятельности</h2>',
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
					        <?= $form->field($user_category, 'category_id')->dropDownList(	
					            	ArrayHelper::map($category, 'id', 'name'),
					            	 [  'prompt'=>'Все категории',
					                    'id'=>'category_id',
					                 ])->label('Выберите категорию услуги') ?>
					        
					        <!-- Подкатегории          -->
					        <?php if (!empty($subcategory)) {?>

					        <?= $form->field($user_category, 'subcategory_id[]')->dropDownList(ArrayHelper::map($subcategory, 'id', 'name'),[
						            //'prompt'=>'Выберите услугу',
						            'id'=>'subcategory_id',
						            'class' => "js-chosen actionss",
									'multiple' => "multiple",
						             ])
						            ->label('Добавьте вид услуги'); ?>

					        <?php } ?>

					        <div class="form-group">
					            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name'=>'save_actions', 'id'=>'save_actions','value'=>'true']) ?>
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

							    $('#save_actions').on('click', function(e){
							        // признак отправки формы ДЛЯ сохранения данных
							        $(this).val('true');
							        $('#action_win').modal('hide');					        
							    })

							    $('.js-chosen.actionss').chosen({
							        width: '100%',
							        no_results_text: 'Совпадений не найдено',
							        placeholder_text_single: 'Выберите услугу',
							        placeholder_text_multiple: 'Любая услуга',
							    });
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
            //Pjax::begin(); 
				//debug($user_subcategory);
            	$user_subcategory_names[]="";
            	foreach($user_subcategory as $u_scat){
            		$user_subcategory_names[]=$u_scat['subcategory']['name'];
            	}
            	$res = sort($user_subcategory_names);
            	
            	foreach($user_subcategory_names as $name) { ?>
					<div class="text profile">
	            		<span><?=$name ?></span>
	            		<div href="" class="text_details profile">от 1 000 Р</div>
	            	</div>					
				<?php 
				}  ?> 					      		       
	        <?php
	        //Pjax::end(); 	 									
			// модальное окно - Вид Деятельности -Конец	
			?>			
			
			<?php //Pjax::end();	?>
		<!-- Категории услуг Конец---------------------------------------  -->        

		<!-- Образование ------------------------------------------------  -->
		        <div class="order_content__subtitle">
	            	<span>Образование</span>            	
	            </div>	
	            <!-- организовать цикл по Курсам обучения-->           
		            <div class="text profile">
		            	<span>Название учебного заведения111</span>
		            	<a href="" class="text_details profile">Редактировать</a>            	
		            </div>
		            <div class="text profile study">
		            	<span>Курсы111 повышения квалификации</span>
		            	<a href="" class="text_details profile">Даты</a>            	
		            </div> 
		            <div class="text profile">
		            	<span>Название учебного заведения222</span>
		            	<a href="" class="text_details profile">Редактировать</a>            	
		            </div>
		            <div class="text profile study">
		            	<span>Курсы222 повышения квалификации</span>
		            	<a href="" class="text_details profile">Даты</a>            	
		            </div> 	                  
		        <a href="" class="text_details">Добавить образование</a>
		   

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
					    'footer' => false,
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
		        		<?= Html::submitButton('Сохранить', [
		        				'class' => 'register__user active__button', 
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
						'id' => 'delete-form',
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
		        				'class' => 'register__user active__button', 
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
        <!-- Удаление профиля Конец -->

               					
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
					'id' => 'delete-form',
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

			<div class="order_buttons">
	            <a href="<?= Url::to('/cabinet/user-tuning') ?>" class="register active">В настройки</a>          
	        </div>          
        	  
		</div>
    </div>        
</div>




                   