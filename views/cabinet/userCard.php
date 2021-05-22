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
						                    <p class="reiting_num"><?= count($orders_list)?></p>
						                    <p class="reiting_text">Заказы</p>
						                </div>
						        	</div>
						        	<div class="st-item">
						        		<div class="b_stat">
						                    <p class="reiting_num"><?= count($reviews)?></p>
						                    <p class="reiting_text">Отзывы</p>
						                </div>
						        	</div>
						        </div>

						        <!-- <div class="buttons">
						        		<a href="" class="register active">Начать работу</a>
							    </div>	 -->				            						     
			    			</div>		    			
			    		</div>

			    		<div class="lk__left">
			    			<div class="filtr filtr_exec ">
			    				<p>Форма работы - <?= $user['workForm']['work_form_name']?></p>
			    				<p>Последний визит - X мин назад</p>	    				
			    				<p>На сайте - <?php
			    				if(!is_null($user['created_at'])) echo(days_from($user['created_at']))." дн."?></p>
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
	           				
	           					
           					<p class="subtitle">О себе</p>
           					<p class="text"><?= $user['myself']?></p>

			    		</div>
           				
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