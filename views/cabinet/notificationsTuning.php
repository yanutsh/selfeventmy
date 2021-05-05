<?php
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

//use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
//use app\components\page\PageAttributeWidget as PAW;
//use app\models\WorkForm;
//use app\models\Sex;
//use app\models\Category;
//use app\models\City;
//use kartik\date\DatePicker;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);


?>
<div class="wrapper__addorder wrapper__addorder__card">
    
    <div class="order_content">
        <div class="notifications_content">
            <div class="order_content__title"><p>Настройка  уведомлений</p></div>        
            
            <?php  Pjax::begin(); 
			//debug($model);
            ?>
            <?php $form = ActiveForm::begin([
            	'action'  => '/cabinet/notifications-tuning',
                'options' => [
                	'id' => 'notification-form',
                    'data-pjax' => true,                    
                    ],
            ]); ?>

            <div class="order_content__subtitle">Пуш-уведомления</div>
            <div class="form-group exactly">                    
                <div class="text text__push">Пуш-уведомления о новых заказах, новых сообщениях (в мобильном приложжении).</div>                           
                <div class="toggle-button-cover"> 
                      <div class="button-cover">
                        <div class="button r" id="button-1">
                          <input type="checkbox" class="checkbox tuning" name= 'NotificationForm[push_notif]' 
	                      <?php if ($model['push_notif']) echo 'checked';?>>
                          <div class="knobs"></div>
                          <div class="layer"></div>
                        </div>
                      </div>
                </div>
            </div>                
                  		
      		<div class="order_content__subtitle">Видимость анкеты</div>
	        <div class="form-group exactly">     
	            <div class="text text__push">Скрыть анкету на сайте от потенциальных клиентов.</div>
	            <div class="toggle-button-cover"> 
                  <div class="button-cover">
                    <div class="button r" id="button-1">
                      <input type="checkbox" class="checkbox tuning" name= 'NotificationForm[show_notif]' 
	                      <?php if ($model['show_notif']) echo ' checked';
	                      else echo '';?>>
                      <div class="knobs"></div>
                      <div class="layer"></div>
                    </div>
                  </div>
	            </div>
	        </div>
	                    
            <div class="order_content__subtitle">Получать письма на почту</div>
            <div class="form-group exactly"> 
	            <div class="text text__push">Письма на E-mail о новых заказах, подходящих заказах, новых сообщениях.</div>
	           	<div class="toggle-button-cover"> 
	                  <div class="button-cover">
	                    <div class="button r" id="button-1">
	                      <input type="checkbox" class="checkbox tuning" name= 'NotificationForm[email_notif]' 
	                      <?php if ($model['email_notif']) echo 'checked';?>>
	                      <div class="knobs"></div>
	                      <div class="layer"></div>
	                    </div>
	                  </div>
	            </div>
	        </div>
	                     
            <div class="order_content__subtitle">Информация о сервисе</div>
            <div class="form-group exactly"> 
	            <div class="text text__push">Письма на E-mail о новых изменениях в сервисе, изменениях в правилах и политике сервиса.</div>           
	            <div class="toggle-button-cover"> 
	                  <div class="button-cover">
	                    <div class="button r" id="button-1">
	                      <input type="checkbox" class="checkbox tuning" name= 'NotificationForm[info_notif]' 
	                      <?php if ($model['info_notif']) echo 'checked';?>>
	                      <div class="knobs"></div>
	                      <div class="layer"></div>
	                    </div>
	                  </div>
	            </div>
	        </div>

	        <div class="order_buttons">
	            <a href="<?= Url::to('/cabinet/user-tuning') ?>" class="register active">В настройки</a>          
	        </div>        

	        <?php 	
			$script = <<< JS
				$('input.checkbox').on('change', function(){
					//alert("Change");
					$('#notification-form').submit();
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