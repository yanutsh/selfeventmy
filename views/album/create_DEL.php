<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

TemplateAsset::register($this);
RegistrationAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Album */

$this->title = 'Create Album';
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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
    	<div class="order_content__subtitle">Создание нового альбома</div>  
	    <h1><?//= Html::encode($this->title) ?></h1>

	    <?php  $model->user_id = $identity['id']; ?>
	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>
	</div>    

</div>
