<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;


// use app\models\WorkForm;
// use app\models\Sex;
// use app\models\Category;
// use app\models\City;
// use kartik\date\DatePicker;
// use yii\widgets\Pjax;

require_once('../libs/user_photo.php');

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
$identity = Yii::$app->user->identity;
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
    
        <?php   if ( $identity['isexec'] ){ // Альбомы показываем Исполнителям?>     
            <div class="order_content__subtitle">Список альбомов</div>                    
            <div class="text">Отредактируйте альбомы или добавьте новый</div>
        
            <?php 
            // debug($dataProvider);
            echo $this->render('../album/index.php', [
                'dataProvider' => $dataProvider,
            ]); ?>
        
        <?php   } ?>

    </div>        
</div>