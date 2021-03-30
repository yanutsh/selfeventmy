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

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

// Получение исходных данных для формы
// $category = Category::find() ->orderBy('name')->asArray()->all();
// $city = City::find() ->orderBy('name')->all();


//debug($identity['isexec']);       
    
?>
<div class="wrapper__addorder wrapper__addorder__card">
    <div class="b_header b_header__tuning">
        <div class="b_avatar b_avatar__tuning">
            <img src="/web/uploads/images/users/<?= $identity['avatar']?>" alt="">
        </div>

       <!--  <div class="clearfix"></div> -->
        <div class="b_text b_text__tuning">
            <span class="fio"><?= $work_form_name." - ".$identity['username'] ?></span>
        </div>
         
    </div>

    <div class="order_content order_content__tuning">
            
        <?php   if ( $identity['isexec'] ){ // Абонементы показываем Исполнителям?>     
            <div class="order_content__subtitle">Абонементы
                <a href="#!">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>        
            <div class="text">Приобретите или посмотрите информацию о <br>действующих абонементах</div>
        <?php   } ?>

        <div class="order_content__subtitle">Настройки уведомлений
            <a href="/cabinet/notifications-tuning">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        <div class="text">Настройки пуш-уведомлений и информация о <br>рассылках</div>

        <div class="order_content__subtitle">Информация о профиле
            <a href="/cabinet/profile-info">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        <div class="text">E-mail и смена пароля, обновление информации <br>о себе и опыта работы
        </div>
            
         
        <div class="order_buttons">
            <a href="/page/logout" class="register active">Выйти из профиля</a>            
        </div> 
        

    </div>        
</div>