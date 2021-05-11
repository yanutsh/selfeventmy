<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

//require_once('../libs/user_photo.php');

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = 'Update Album: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
//debug($album_photoes);
?>

<div class="wrapper__addorder wrapper__addorder__card">
    <div class="b_header b_header__tuning">
        <div class="b_avatar b_avatar__tuning">
            <img src="<?= user_photo($identity['avatar'])?>" alt="">
        </div>

       <!--  <div class="clearfix"></div> -->
        <div class="b_text b_text__tuning">
            <span class="fio"><?= $work_form_name." - ".$identity['username'] ?></span>
        </div>
        <?php   if ( $identity['isconfirm'] ){ //Если профиль проверен-показываем?>     
            <div class="checked">Профиль проверен</div>             
        <?php   } ?>         
    </div>

    <div class="order_content order_content__tuning">
    	<div class="order_content__subtitle">Редактирование альбома</div>  
	    
	    <?= $this->render('_form', compact('model', 'album_photoes','id')) ?>
	</div>    

</div>
