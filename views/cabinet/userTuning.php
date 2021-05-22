<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
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
        <?php   
        if ( $identity['isconfirm'] && $identity['isexec']){ //Если Исполнитель и профиль проверен-показываем?>     
            <div class="checked">Профиль проверен</div>             
        <?php   } ?>
         
    </div>    

    <div class="order_content order_content__tuning">
    
        <?php   
        if ( $identity['isexec'] && $identity['isconfirm'] ){ 
        //Если профиль проверен-показываем РЕЙТИНГ> 
        }elseif($identity['isexec']){ // Предупреждение о проверке документов ?>  
            <div class="check_need">
                <div class="order_content__subtitle">Проверка данных</div>
                <div class="text">Пройдите верификацию для подтверждения данных в профиле и получения статуса "проверенный профиль"</div>
                <div class="text text_moon">Проверка не займет более 5 минут. 
                    <a href="<?=url::to('/doc/update')?>">Пройти проверку</a>
                </div>
            </div>
        <?php } ?> 

        <?php   if ( $identity['isexec'] ){ // Абонементы показываем Исполнителям?>     
            <div class="order_content__subtitle">Абонементы
                <a href="<?=Url::to(['/cabinet/abonement-choose']) ?>">
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

         <?php   if ( $identity['isexec'] ){ // Абонементы показываем Исполнителям?>     
            <div class="order_content__subtitle">Портфолио
                <!-- <a href="/cabinet/album-list"> -->
                <a href="/album/index">    
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>        
            <div class="text">Фото и видео ваших работ, которые видят<br>клиенты в вашем профиле</div>
        <?php   } ?>

        <div class="order_content__subtitle">
            <img src="/web/uploads/images/envelope.png" alt="">
            <a href="/cabinet/<?php
                if($identity['isexec']) echo"exec-card?id=".$identity['id']; 
                else echo"user-card?id=".$identity['id'];?>" class="text text__anketa">Просмотр анкеты</a>
        </div>    
            
         
        <div class="order_buttons">
            <a href="/page/logout" class="register active">Выйти из профиля</a>            
        </div> 
        

    </div>        
</div>