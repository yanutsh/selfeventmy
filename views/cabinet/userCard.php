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

//require_once('../libs/days_from.php');
//require_once('../libs/user_photo.php');
//require_once('../libs/convert_date_en_ru.php');

TemplateAsset::register($this);
CabinetAsset::register($this);

	//$identity = Yii::$app->user->identity;
	$avatar = $user['avatar'];		
?>

<div class="container">

	<div class="wrapper">
    	<div class="row">

			<div class="wrapper__lk">
		    
		    	<div class="col-md-4">
		    		<div class="lk__left">
		    			<div class="filtr filtr_exec ">
		    				
		    				<div class="b_avatar b_avatar__exec">   					
					            <img src="<?= user_photo($user['avatar'])?>" alt="">
					        </div>

					        <!-- star rating data-id="user_id" **************************-->
								<script>  
								  	localStorage.removeItem('star_rating');
								  	localStorage.removeItem('star_rating_get'); 
								</script>

								<?php 
								// только для отображения рейтинга:
								require_once($_SERVER['DOCUMENT_ROOT'].'/libs/star_reting_get_html.php') 

								// для установки рейтинга:
								//require_once($_SERVER['DOCUMENT_ROOT'].'/libs/star_reting_html.php') ?>

							<!-- star rating data-id="page-1" Конец ********************-->
		        

					       <!--  <div class="clearfix"></div> -->
					        <div class="b_text b_text__exec">
					            <span class="fio"><?= $user['workForm']['work_form_name']." - ".$user['username'] ?></span>
					            
					            <?php // профиль проверен?
					            if($user['isconfirm']) {?>					            
					            	<p  class="checked"><span>Профиль проверен</span></p>
					            <?php }else{ ?>
					            	<p  class="checked checked__no"><span>Профиль не проверен</span></p>
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
					                    <p class="reiting_num"><?php 
					                    if($orders_list) echo count($orders_list);
					                    else echo("0"); ?>	                    	
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

					        <?php if($user['isexec']) {?>	
					         	<p  class="top100">?Входит в ТОП 100 исполнителей?</p>
					         	
						        <div class="buttons">
						        	<a href="" class="register active">Начать работу</a>
						        	<a href="" class="register">Календарь исполнителя</a>
							    </div>
						    <?php } ?>

		    			</div>		    			
		    		</div>

		    		<div class="lk__left">
		    			<div class="filtr filtr_exec ">
		    				<p>Форма работы - <?= $user['workForm']['work_form_name']?></p>
		    				<p>Последний визит - <?php
		    					$tfrom = time_from($max_date['update_time']);
		    					if($tfrom['days'] > 0) echo $tfrom['days']." дн. назад"; 
		    					elseif($tfrom['hours'] > 0) echo $tfrom['hours']." час. назад";
		    					else echo $tfrom['minutes']." мин. назад" 
		    					?></p>
		    				<?php if($user['isexec']) {?>
		    					<p>Сегодня - ?Свободен?</p>
		    				<?php } ?>	
		    				<p>На сайте - <?= days_from($user['created_at'])." дн."?></p>
		    			</div>
		    		</div>		
		    	</div>


		    	<div class="col-md-8">
		    		<div class="lk__main lk__main__exec">
		    			
		    			<?php if($user['isexec']) {?>
			    			<p class="subtitle">Категории услуг</p>
			    			<div class="title"><?php
				    			$c=""; 
			    				foreach($user['category'] as $cat) { 			    			if ($c=="") $c = $cat['name'];
			           				else $c .= ", ".$cat['name'];
			           			}
			           			echo $c; ?>		      		
			    			</div>
			    		<?php } ?>
			    			
		    			<a href="mailto:<?= 'yanutsh@m.ru'?>" target="_blank">
			    			<div class="b-right">
				    			<div class="letter">
				    				<img src="/web/uploads/images/envelope-wight.png" alt="Написать">
				                    <div><span>Написать</span></div>		               
	           					</div>
           					</div>
       					</a>
       					
       					<?php if($user['isexec']) {?>	
		       				<div class="exec_prepayment">
		       					<?php if($user['isprepayment']==0) echo"Работает без предоплаты за услуги"; else echo"Работает c предоплатой"; ?>
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
           					<p class="text"><?= $user['myself']?></p>
       					
           				<?php if($user['isexec']) {?>	
	       					<p class="subtitle ">Услуги</p>
	       						<div>
	       						<ul class="service">           							
	       							<?php 			           				
			           				foreach($user['subcategory'] as $sc) { ?>
			           					<!-- <p class="text subtitle_text"><?= $sc['name'];?></p> -->
			           					<li><?= $sc['name'];?></li>
			           				<?php } ?>
			           			</ul>	
	       						</div>

	       					<p class="subtitle">Образование</p>
	       						<div>
	       						<?php  
	       						foreach($user['userEducations'] as $ue) { ?>	
		           					<p class="text subtitle_text">
		           						<?=$ue['institute'] ?>
		           					</p>
		           					<p class="text_slow">
		           						<?=$ue['course'] ?>
		           						<span> <?php 
		           						if(!is_null($ue['end_date'])) 
		           							echo("окончил ".convert_date_en_ru($ue['end_date'])); ?>
		           						</span>	
		           					</p>
		           				<?php } ?>			           					
	           					</div>

	           				<p class="subtitle">Портфолио</p>
	           				<?php 
	           				// цикл по альбомам 	
	           				foreach($albums as $alb) { ?>
	           				
		           				<p class="text subtitle_text">Альбом: <?= $alb['album_name']?></p>
		           				<div class="slider portfolio-slider">	
						            <?php 
						            // цикл по фоткам альбома
						            foreach($alb['albumPhotos'] as $photo){ ?>}
						                <div>
						                	<a href="/web/uploads/images/portfolio/<?= $photo['photo_name']?>" class="highslide" onclick="return hs.expand(this)" >
						                		<img src="/web/uploads/images/portfolio/<?= $photo['photo_name']?>" alt="">
						                    </a>
						                </div>
						            <?php } ?>
						        </div>

					        <?php } ?>	
				        <?php } ?>	
		    		</div>

		    		<!-- Заказы  олько для карточки Заказчика-->
		    			<?php if($user['isexec']==0) {?>	
				    		<div class="lk__main lk__main__exec">
				    			<div class="refer" id="show_user_orders">Смотреть все</div>
					    		<p class="subtitle">Заказы</p>
		           					<p class="text">
		           						<!-- Личных заказов размещено -->
		           						Всего заказов размещено - <?= count($orders_list)?>
		           						<?php //debug($orders_list) ?>
		           					</p>
		           					<div class="user_orders">
		           						<?php 
		           						echo $this->render('@app/views/partials/orderslist.php', compact('orders_list', 'pages'));
		           						 ?>
		           					</div>	           						
				    		</div>
			    		<?php }	 ?>
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
