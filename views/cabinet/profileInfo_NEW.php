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
               
	        <!-- Категории услуг---------------------------------------------  -->
	        	<?php Pjax::begin(); ?>
	            <div class="order_content__subtitle">
	            	<span>Сфера деятельности</span> 
			       
			        <?php 
					// модальное окно - Добавить Вид деятельности					
						Modal::begin([
						    'header' => '<h2>Добавление деятельности</h2>',				    
						    'id' => "action_win",	
						    'toggleButton' => [
						     	'label' => 'Добавление/Удаление услуги',
						     	'tag' => "a",
						     	'class' => 'text_details profile',
						     	'id' => 'modal_action',
						     ],
						    'footer' => 'низ',
						]); ?>

							<?php Pjax::begin(); ?>							
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

							<?php Pjax::begin(); ?>
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
						<?php 
						Modal::end();										
					// модальное окно - Вид Деятельности -Конец	
					?>					    			
				</div>
				<!-- организовать цикл по услугам--> 
		        <div class="text"><?php 						
		        	foreach($user_subcategory as $u_cat) { ?>
					<div class="text profile">
	            		<span><?=$u_cat['subcategory']['name'] ?></span>
	            		<div class="text_details profile">от 1 000 Р</div>
	            	</div>					
					<?php 
					}  ?>
				</div>			
				<?php
						$script = <<< JS
							// Закрытие фона модального окна
						    $('#add-subcategory-button').click(function(e){
						  	    // отправка формы по pjax и потом удаление фона:   	   	
						     	//$('.modal-backdrop.fade.in').css('display','none'); 
						     	//$('body').removeAttr('class');  	    	
							 });

						    $('#category_id').on('change',function(event){
						    	//event.preventDefault();							
								$('#add-category-form').submit();

						    });

						    $('.js-chosen.city').chosen({
						        width: '100%',
						        no_results_text: 'Совпадений не найдено',
						        placeholder_text_single: 'Выберите город',
						        placeholder_text_multiple: 'Любой город',
						    });
						JS;
							//маркер конца строки, обязательно сразу, без пробелов и табуляции
							$this->registerJs($script, yii\web\View::POS_READY);
				?>
				<?php Pjax::end();	?>
			<!-- Категории услуг Конец---------------------------------------  -->

		</div>
    </div>        
</div>




                   