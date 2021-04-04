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

require_once('../libs/user_photo.php');

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
			    			<div class="filtr filtr_exec ">
			    				
			    				<div class="b_avatar b_avatar__exec">   					
						            <img src="<?= user_photo($exec['avatar'])?>" alt="">
						        </div>						        

						       <!--  <div class="clearfix"></div> -->
						        <div class="b_text b_text__exec">
						            <span class="fio"><?= $exec['workForm']['work_form_name']." - ".$exec['username'] ?></span>
						            <p  class="check"><span>Профиль проверен</span></p> 
						        </div>

						        <div class="statistic">
						        	<div class="st-item">
										<div class="b_stat">
						                    <p class="reiting_num first">5.0</p>
						                    <p class="reiting_text">Рейтинг</p>
						                </div>
						        	</div>
						        	<div class="st-item">
						        		<div class="b_stat">
						                    <p class="reiting_num">1567</p>
						                    <p class="reiting_text">Заказы</p>
						                </div>
						        	</div>
						        	<div class="st-item">
						        		<div class="b_stat">
						                    <p class="reiting_num">150</p>
						                    <p class="reiting_text">Отзывы</p>
						                </div>
						        	</div>
						        </div>

						         <p  class="top100">Входит в ТОП 100 исполнителей</p>

						        <div class="buttons">
						        	<a href="" class="register active">Начать работу</a>
						        	<a href="" class="register">Календарь исполнителя</a>
							    </div> 
			    			</div>		    			
			    		</div>

			    		<div class="lk__left">
			    			<div class="filtr filtr_exec ">
			    				<p>Форма работы - физ. лицо</p>
			    				<p>Последний визит - 5 мин назад</p>
			    				<p>Сегодня - Свободен</p>
			    				<p>На сайте - 799 дней</p>
			    			</div>
			    		</div>		
			    	</div>


			    	<div class="col-md-8">
			    		<div class="lk__main lk__main__exec">
			    			<p class="title">Фотограф</p>
			    			<a href="mailto:<?= 'yanutsh@m.ru'?>" target="_blank">
				    			<div class="b-right">
					    			<div class="letter">
					    				<img src="/web/uploads/images/envelope-wight.png" alt="Написать">
					                    <div><span>Написать</span></div>			               
		           					</div>
	           					</div>
           					</a>

           					<p>г. Москва</p>

           					<div class="exec_prepayment">
           						Работает без предоплаты за услуги
           					</div> 

           					<p class="subtitle">Города</p>
	           					<p class="text">
	           						Москва, Московская обл.
	           					</p>
           					<p class="subtitle">О себе</p>
	           					<p class="text">
	           						Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos sed vitae, nostrum omnis molestias optio voluptate maxime a officiis beatae error ipsa doloribus est deserunt nemo ab aut odit adipisci quis eius, laudantium dignissimos consectetur quo, fugit hic. Nam totam, doloremque harum quis esse dicta architecto possimus dignissimos non id corporis placeat, ut quam accusamus. Sed adipisci repellat, nemo autem maxime vel impedit, iusto error tenetur pariatur veritatis eaque dolor aspernatur quasi enim illum expedita ratione officia corporis! Inventore rerum quia, libero nam officia mollitia! Voluptate vitae, odit iure enim consequatur modi harum aut cupiditate, veniam dignissimos libero ab architecto?
	           					</p>
           					<p class="subtitle ">Услуги</p>
           						<div>
	           						<p class="text subtitle_text">Ведение праздников</p>
	           						<p class="text subtitle_text">Ведение праздников</p>
           						</div>

           					<p class="subtitle">Образование</p>
           						<div>
		           					<p class="text subtitle_text">Наименование Учебного заведения</p>
		           					<p class="text_slow">Курсы повышения квалификации</p>
		           					<p class="text subtitle_text">Наименование Учебного заведения</p>
		           					<p class="text_slow">Курсы повышения квалификации</p>
	           					</div>

	           				<p class="subtitle">Портфолио</p>
	           				<p class="text subtitle_text">Название альбома 1</p>
	           				<div class="portfolio-slider">
					            <?php foreach($order['orderPhotos'] as $photo){ ?>}
					                <div><img src="/web/uploads/images/orders/<?= $photo['photo']?>" alt=""></div>
					            <?php } ?>
					        </div>

					        <p class="text subtitle_text">Название альбома 2</p>
	           				<div class="portfolio-slider">
					            <?php foreach($order['orderPhotos'] as $photo){ ?>}
					                <div><img src="/web/uploads/images/orders/<?= $photo['photo']?>" alt=""></div>
					            <?php } ?>
					        </div>

	
			    		</div>
			    			
			    		</div>			    			
			    	</div>	
			   		
		    	</div>

	    	</div>
	    </div>

	</div>
