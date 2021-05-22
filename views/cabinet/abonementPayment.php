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
            <?php  Pjax::begin();  ?>

            <div class="messages">
                <?php if( Yii::$app->session->hasFlash('msg_error') ): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('msg_error'); ?>
                        </div>
                <?php endif;?>
            </div>      
            
           	<div class="subtitle">Подтверждение оплаты</div>                               

            <?php  //Pjax::begin(); 
			//debug($model);
            ?>
            <?php $form = ActiveForm::begin([
            	'action'  => '/cabinet/abonement-payment?id='.$abonement['id']."&duration=".$abonement['duration']."&freeze=".$freeze,
                'options' => [
                	'id' => 'abonement-form',
                    'data-pjax' => true,                    
                    ],
            ]); ?>

            
			<div class="tarif_box">
		            	<div class="flex_sp_bw row1">
		            		<div class="text1"><?= $abonement['name'] ?></div>
		            		<div class="text2"><?php 
		            			if(!is_null($abonement['price_old']))  echo($abonement['price_old']." ₽"); ?></div>
		            	</div>
		            	<div class="flex_sp_bw row2">
		            		<div class="text1"><?= $abonement['description'] ?></div>
		            		<div class="text2"><?= $abonement['price'] ?> ₽</div>
		            	</div>
		    </div>
	           
           <div class="text text__abonement">Для приобретения абонемента у вас должна быть необходимая сумма на балансе.</div>

           <div class="form-group">
                <?= Html::submitButton('Приобрести', [
                    'class' => 'register__user active__button save', 
                    'name' => 'abonement-pay-button',
                    'id' => 'abonement-pay-button',
                    'value' => 'true',
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Выбрать другой абонемент', [
                    'class' => 'register__user active__button save', 
                    'name' => 'abonement-choose-button',
                    'id' => 'abonement-choose-button',
                    'value' => 'true',
                ]) ?>
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