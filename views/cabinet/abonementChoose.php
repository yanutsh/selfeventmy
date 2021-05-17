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

            <?php  Pjax::begin(['enablePushState' => true]); 
			//debug($model);
            ?>
            <?php $form = ActiveForm::begin([
            	'action'  => '/cabinet/abonement-choose',
                'options' => [
                	'id' => 'abonement-form',
                    'data-pjax' => true,                    
                    ],
            ]); ?>

            <div class="form-group exactly">                    
                <div class="text text__push">Тарифы с заморозкой</div>                           
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
			foreach ($abonement as $key => $value) { ?>
				<a href="<?= Url::to(['abonement-payment','id'=> $value['id']]) ?>" data-pjax = '0'>
		            <div class="tarif_box">
		            	<div class="flex_sp_bw row1">
		            		<div class="text1"><?= $value['name'] ?></div>
		            		<div class="text2"><?php 
		            			if(!is_null($value['price_old']))  echo($value['price_old']." ₽"); ?></div>
		            	</div>
		            	<div class="flex_sp_bw row2">
		            		<div class="text1"><?= $value['description'] ?></div>
		            		<div class="text2"><?= $value['price'] ?> ₽</div>
		            	</div>
		            </div>
	            </a>
            <?php } ?>

            <div class="order_buttons">
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