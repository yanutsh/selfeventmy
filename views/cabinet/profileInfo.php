<?php
use yii\helpers\Html;
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
						<?= $form->field($user, 'myself')->textarea(['style'=>'resize:vertical', 'rows'=>'5']); //->label(false); ?>
	    				
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
           
            <?php // Вывод данных только для Исполнителя            
            if ($identity['isexec']) {?>
	            <div class="order_content__subtitle">
	            	<span>Города</span>
	            	<a href="" class="text_details profile">Редактировать</a>
	            </div>
	            <div class="text">Москва, Область</div>
	            

	            <div class="order_content__subtitle">
	            	<span>Сфера деятельности</span>
	            	<a href="" class="text_details profile">Редактировать</a>
	            </div>	
	            <!-- организовать цикл по услугам-->           
		            <div class="text profile">
		            	<span>Ведение праздников</span>
		            	<div href="" class="text_details profile">от 1 000 Р</div>
		            </div>
		            <div class="text profile">
		            	<span>Ведение концертов</span>
		            	<div href="" class="text_details profile">от 3 000 Р</div>
		            </div>	      
		        <a href="" class="text_details">Добавить деятельность</a>

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
		    <?php } ?>    

		<!-- Контактные данные -->
	        <?php Pjax::begin(); ?>
	        <div class="order_content__subtitle">
            	<span>Контактные данные</span>
            	<?php 
				// модальное окно - Контактные данные					
					Modal::begin([
					    'header' => '<h2>Контактные данные</h2>',
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

            <div class="order_content__subtitle">
            	<span>Удалить профиль</span>
            	<a href="" class="text_details profile">Редактировать</a>
            </div>
            <div class="text">Навсегда удалить профиль.</div>            

        </div>   

    </div>        
</div>




                   