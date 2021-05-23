<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);


?>
<div class="wrapper__addorder wrapper__addorder__card">
    
    <div class="order_content">
        <div class="notifications_content __abonement">
            <div class="order_content__title"><p>Абонемент</p></div>        
            
            <div class="abonement_block">
            	<div class="text">Абонементы с безлимитом на отклики к заказам</div>
            	<img src="/web/uploads/images/abonement_24px_wite.png" alt="">            	
            </div>

            <div class="descript">
            	<span>Вы можете приобрести тариф с заморозкой, чтобы приостановить его действие на период отдыха или болезни.</span>
            	<br><br>
            	<span>Абонемент можно заморозить 1 раз за период его действия на срок от 5 до 15 дней, в зависимости от тарифа.</span>

            </div>

            <div class="title">Тарифы</div>

            <!-- Сообщение о покупке Абонемента -->
                <?php if( Yii::$app->session->hasFlash('msg_success') ): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('msg_success'); ?>
                        </div>
                <?php endif;?>
            <!-- Сообщение о покупке   -->

            <?php  Pjax::begin(['enablePushState' => true]); 
			//debug($model);
            // сообщение об ошибке
            if( Yii::$app->session->hasFlash('msg_error') ): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('msg_error'); ?>
                        </div>
            <?php endif  ?>

            <?php $form = ActiveForm::begin([
            	'action'  => '/cabinet/abonement-choose',
                'options' => [
                	'id' => 'abonement-form',
                    'data-pjax' => true,                    
                    ],
            ]); ?>

            <div class="form-group exactly">                    
                <div class="text text__push">Показать Тарифы с заморозкой</div>                           
                <div class="toggle-button-cover"> 
                      <div class="button-cover">
                        <div class="button r" id="button-1">
                          <input type="checkbox" class="checkbox tuning" name= 'freeze' 
	                      <?php if ($freeze) echo 'checked';?>>
                          <div class="knobs"></div>
                          <div class="layer"></div>
                        </div>
                      </div>
                </div>
            </div>   

			<?php //debug($abonement);
			foreach ($abonement as $key => $ab) { ?>
				<div class="tarif_block">
					<!-- Отметка лучший выбор -->
			        <?php if ($ab['best'])  { ?>
		            	<div class="best_choice"><span>Выгодно</span></div>	            	
		            <?}?>

                    <?php 
                    $tarif_box_class = "";
                    if ($ab['id'] == $user_abonement['abonement_id'] &&                    $user_abonement['end_date'] > date('Y-m-d H:i:s')) { 
                        if($user_abonement['abonement_status']=='заморожен') 
                                $tarif_box_class = "frosen";
                    ?>
		                <div class="best_choice active"><span>Действующий</span></div>
                    <?}?>

					<a href="<?= Url::to(['abonement-payment','id'=> $ab['id'],'freeze'=>$freeze]) ?>"  data-pjax = '0' title="Купить этот абонемент">
			            <div class="tarif_box <?= $tarif_box_class ?>">
			            	<div class="flex_sp_bw row1">
			            		<div class="text1"><?= $ab['name'] ?></div>
			            		<div class="text2"><?php 
			            			if(!is_null($ab['price_old']))  echo($ab['price_old']." ₽"); ?></div>
			            	</div>
			            	<div class="flex_sp_bw row2">
			            		<div class="text1"><?= $ab['description'] ?></div>
			            		<div class="text2"><?= $ab['price'] ?> ₽</div>
			            	</div>		            	
			            </div>
		            </a>
	            </div>

                <?php // если абонемент действующий  
                if( $ab['id'] == $user_abonement['abonement_id'] &&
                    $user_abonement['end_date'] > date('Y-m-d H:i:s') ) 
                {
                    $rez = convert_datetime_en_ru($user_abonement['end_date']);
                    $end_date = $rez['dmYHis'];  ?>

                    <div class="duration">Действителен до <?= $end_date ?></div>
                    
                    <?php
                    // Если абонемент с заморозкой и НЕ заморожен
                    if( $ab['freeze_days']>0 &&
                        $user_abonement['abonement_status']<>"заморожен" )
                    {?>
                        <a href="<?= Url::to(['abonement-freeze', 
                            'id'=>$ab['id'], 
                            'user_id' => $user_abonement['user_id'], 
                            'freeze_days'=> $ab['freeze_days'] ])?>" 
                            class="freeze_days">Заморозить на <?= $ab['freeze_days'] ?> дней</a>
                    <?php }
                    // Если абонемент с заморозкой и Заморожен
                    elseif ( $ab['freeze_days']>0 &&
                            $user_abonement['abonement_status'] == "заморожен" )
                    {   // определяем дату окончания заморозки
                        $start_freeze_date = strtotime($user_abonement['freeze_date']);          
                        $end_freeze_date = strtotime('+'.$ab['freeze_days'].' days', $start_freeze_date); 
                        $end_freeze_date = date('d.m.Y H:i:s',$end_freeze_date);
                        ?>
                        <div class="frozen">Заморожен до <?= $end_freeze_date ?></div> 
                    <?php } 
                } ?>


            <?php } ?>
            <!-- конец цикла по абонементам -->

            <div class="order_buttons abonement">
	            <a href="<?= Url::to('/cabinet/user-tuning') ?>" class="register active">В настройки</a>          
	        </div>

            <?php 	
			$script = <<< JS
				$('input.checkbox').on('change', function(){
					$('#abonement-form').submit();
				})
			JS;
			//маркер конца строки, обязательно сразу, без пробелов и табуляции
			$this->registerJs($script, yii\web\View::POS_READY);
			?>
	       
            <?php $form = ActiveForm::end()?>    
        	<?php  Pjax::end(); ?>
            
        </div>   

    </div>        
</div>