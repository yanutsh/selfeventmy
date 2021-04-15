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

require_once('../libs/days_from.php');
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
						            <img src="<?= user_photo($user['avatar']) ?>" alt="">
						        </div>						        

						       <!--  <div class="clearfix"></div> -->
						        <div class="b_text b_text__exec">
						            <span class="fio"><?= $user['username'] ?></span>
						            <?php 
						            if ($identity['isexec']) { ?>
						            	<p  class="checked">Профиль проверен</p>
						            <?php } ?>	 
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
						                    <p class="reiting_num">
						                    <?php
						                    if($orders_list) echo count($orders_list);
						                    else echo("0"); 
						                    ?>
						                    </p>
						                    <p class="reiting_text">Заказы</p>
						                </div>
						        	</div>
						        	<div class="st-item">
						        		<div class="b_stat">
						                    <p class="reiting_num"><?php 
						                    if($reviews) echo count($reviews);
						                    else echo "0";
						                    	?>
						                    </p>
						                    <p class="reiting_text">Отзывы</p>
						                </div>
						        	</div>
						        </div>

						        <?php 
						        if ($identity['isexec']) { ?>
					            	<p  class="rose">Входит в TOP-100 сполнителей</p>
									<div class="buttons">
						        		<a href="" class="register active">Начать работу</a>
							    	</div>

					            <?php }
					            else{ ?>
							        <div class="buttons">
							        	<a href="" class="register active">Стать исполнителем</a>
								    </div>
								<?php }?>								     
			    			</div>		    			
			    		</div>

			    		<div class="lk__left">
			    			<div class="filtr filtr_exec ">
			    				<p>Форма работы  - <?= $user['workForm']['work_form_name']?></p>
			    				<p>Последний визит - X мин назад</p>	    				
			    				<p>На сайте - <?= days_from($user['created_at'])." дн."?></p>
			    			</div>
			    		</div>		
			    	</div>


			    	<div class="col-md-8">
			    		<div class="lk__main lk__main__exec">
			    			<p class="title"><?= $user['workForm']['work_form_name']." - ".$user['username'] ?></p>
			    			<a href="mailto:<?= 'yanutsh@m.ru'?>" target="_blank">
				    			<div class="b-right">
					    			<div class="letter">
					    				<img src="/web/uploads/images/envelope-wight.png" alt="Написать">
					                    <div><span>Написать</span></div>			               
		           					</div>
	           					</div>
           					</a>

           					<!-- <p>г. Москва</p> -->

           					<?php  
           					 if ($identity['isexec']) { ?>
	           					<div class="exec_prepayment">
	           						Работает без предоплаты за услуги
	           					</div>

	           					<p class="subtitle">Города</p>
		           				<p class="text">
		           					<?php 
			           				$c="";
			           				foreach($user['cities'] as $city) {
			           					if ($c=="") $c = $city['name'];
			           					else $c .= ", ".$city['name'];
			           				}
			           				echo $c;
			           				?>		
		           				</p>
	           				<?php } ?>
	           					
           					<p class="subtitle">О себе</p>
           					<p class="text"><?= $identity['myself']?></p>

           					<?php  
           					if ($identity['isexec']) { ?> 
	           					<div class="order_content__subtitle">
	            					<span>Сфера деятельности</span>
	            				</div>	
	            				<div>
           						<ul class="service">           							
           							<?php 			           				
			           				foreach($user['subcategory'] as $sc) { ?>
			           					<div class="text profile">			
				           					<li><?= $sc['name'];?></li>
				           					<div class="text_details profile">от 1 000 Р</div>
				           				</div>
			           				<?php } ?>
			           			</ul>	
           						</div>			      
			       
			               		<div class="order_content__subtitle">
					            	<span>Образование</span>            	
					            </div>	
					            <!-- организовать цикл по Курсам обучения-->           
						        <div>
           						<?php  
           						foreach($user['userEducations'] as $ue) { ?>	
		           					<p class="text subtitle_text">
		           						<?=$ue['institute'] ?>
		           					</p>
		           					<p class="text_slow">
		           						<?=$ue['course'] ?>
		           						<span>окончил <?= convert_date_en_ru($ue['end_date'])?></span>	
		           					</p>
		           				<?php } ?>			           					
	           					</div>
						        <!-- организовать цикл по Курсам обучения Конец-->

						        <div class="order_content__subtitle">
					            	<span>Портфолио</span>            	
					            </div>
					            <!-- организовать цикл по Альбомам обучения-->
					            <?php 
		           				// цикл по альбомам 	
		           				foreach($albums as $alb) { ?>
		           				
			           				<p class="text subtitle_text">Альбом: <?= $alb['album_name']?></p>
			           				<div class="slider portfolio-slider">	
							            <?php 
							            // цикл по фоткам альбома
							            foreach($alb['albumPhotos'] as $photo){ ?>}
							                <div><img src="/web/uploads/images/portfolio/<?= $photo['photo_name']?>" alt="">
							                </div>
							            <?php } ?>
							        </div>

						        <?php } ?>


							<?php 
							} ?>

			    		</div>

			    		<?php  
           				if (!$identity['isexec']) { ?>
			    			<!-- Заказы -->
				    		<div class="lk__main lk__main__exec">
				    			<div class="refer" id="show_user_orders">Смотреть все</div>
					    		<p class="subtitle">Заказы</p>
		           					<p class="text">
		           						<!-- Личных заказов размещено -->
		           						Всего заказов размещено - <?= count($orders_list)?>
		           					</p>
		           					<div class="user_orders">
		           						<?php 
		           						echo $this->render('@app/views/partials/orderslist.php', compact('orders_list', 'pages'));
		           						 ?>
		           					</div>	
				    		</div>
				    	<?php 
				    	} ?>

			    		<!-- Отзывы -->
			    		<div class="lk__main lk__main__exec">
			    			<div class="refer" id="show_user_reviews">Смотреть все</div>
				    		<p class="subtitle">Отзывы</p>
	           					<p class="text">
	           						<!-- Отзывов о пользователе оставлено -->
	           						Всего отзывов получено - <?= count($reviews)?>
	           					</p>
	           					<div class="review_list">
	           						<?php foreach($reviews as $r) { ?>
	           							<div class="review_header">
	           								<img src="<?= user_photo($r['fromUser']['avatar'])?>" alt="">
	           								
	           								<div class="fio_text">
	           									<div class="fio">
	           										<?= $r['fromUser']['username']?>
	           									</div>
	           									<div class="stars">Звезды</div>
	           								</div>	           								.
	           							</div>

	           							<div class="review_details">
	           								<p><?= $r['review'] ?>
	           							</div>
	           						<?php } ?>
	           					</div>	
			    		</div>			    			
			    	</div>	
			   		
		    	</div>

	    	</div>
	    </div>

	</div>
<?php 	
$script = <<< JS
	// сворачивание заказов
	$('#show_user_orders').on('click',function(e){
		//alert("Все отзывы");
		if ($(this).text()=="Смотреть все") {
			$(this).text("Свернуть");
			$('.user_orders').css('display','block');
		}else{
			$(this).text("Смотреть все");
			$('.user_orders').css('display','none');			
		}	
	})

	// сворачивание отзывов
	$('#show_user_reviews').on('click',function(e){
		//alert("Все отзывы");
		if ($(this).text()=="Смотреть все") {
			$(this).text("Свернуть");
			$('.review_list').css('display','block');
		}else{
			$(this).text("Смотреть все");
			$('.review_list').css('display','none');			
		}	
	})
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>