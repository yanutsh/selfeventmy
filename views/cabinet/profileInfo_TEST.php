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

<?php //Pjax::begin(); ?>			
<div class="wrapper__addorder wrapper__addorder__card">
    
    <div class="order_content">
        <div class="notifications_content">
            <div class="order_content__title">Информация о профиле</div> 
       	    
	        <!-- Категории услуг---------------------------------------------  -->
	        	
	            <div class="order_content__subtitle">
	            	<span>Сфера деятельности</span>
	            </div>
				
				<?php //Pjax::begin(); ?>							
					<div class='subtitle'>Выбраны услуги:</div>
					<table class='user_cities'>

						<?php 
						//debug($user_subcategory);
						// foreach($user_subcategory as $u_cat) 
						// {?>
							<tr>
								<td class="city_name"><?//= $u_cat['subcategory']['name'] ?></td>
								<td class="city_del">
									<a href="/cabinet/delete-user-subcategory?subcategory_id=<?//= $u_cat['subcategory']['id']?>">
										<img src="/web/uploads/images/delete_icon_32px.png" alt="">
									</a>								
								</td>
							</tr>
						<?php //} ?>

					</table>
				<?php //Pjax::end(); ?>
				
			<!-- Категории услуг Конец---------------------------------------  --> 

			<!-- Города------------------------------------------------------  --> 
	         	
	            <div class="order_content__subtitle">
	            	<span>Города</span>
	            </div>	
            	
            	<div class="city">
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
	            </div>           
	               
	        <!-- Города -Конец	---------------------------------------------- -->  

		    <!-- Тестовая страница -->
			    <h3>Тестовая страница</h3>
			    
			    <div class="col-sm-12 col-md-6">
			        <?php Pjax::begin(); ?>
			        <?= Html::a("Новый случайный ключ", ['mytest/key'], ['class' => 'btn btn-lg btn-primary']) ?>
			        <h3>Ключ=<?= $randomKey ?><h3>
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
			    </div>

			    <div class="col-sm-12 col-md-6">
			        <?php Pjax::begin(); ?>
			        <?= Html::a("Новая случайная строка", ['mytest/string'], ['class' => 'btn btn-lg btn-primary']) ?>
			        <h3>Строка=<?= $randomString ?></h3>
			        <?php Pjax::end(); ?>
			    </div>	

			    
		    <!-- Тестовая страница  Конец-->
		        	  
		</div>
    </div>        
</div>