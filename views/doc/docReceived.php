<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = 'Update Doc Photo';
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
?>

<div class="wrapper wrapper__doc">    
    <div class="order_content order_content__tuning">
        
            <div class="order_content__title">Подтверждение данных</div>
            
            <img class="doc_received" src="/web/uploads/images/confirm_docs.png" alt="">

            <div class="subtitle__doc">
                <span>На проверку данных требуется время от 3-х минут до <br> 3-х часов. Пожалуйста ожидайте, информация<br>автоматически обновится в профиле.
                </span>
            </div>

            <div class="order_buttons">
                <a href="<?= Url::to('/cabinet/user-tuning') ?>" class="register active">В настройки</a>          
            </div>               
    	
	</div>    

</div>