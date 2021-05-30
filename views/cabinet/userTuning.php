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
        if ( $identity['isconfirm']==1 && $identity['isexec']){ //Если Исполнитель и профиль проверен-показываем?>     
            <div class="checked">Профиль проверен</div>             
        <?php   } ?>
         
    </div>    

    <div class="order_content order_content__tuning">
    
        <?php   
        if ( $identity['isexec'] ) {
            if( $identity['isconfirm']==1 ){  ?>
                <!-- Если профиль проверен-показываем РЕЙТИНГ> -->
                <div class="statistic statistic__tuning">
                    <div class="st-item">
                        <div class="b_stat">
                            <p class="reiting_num first">5.0</p>
                            <p class="reiting_text">Рейтинг</p>
                        </div>
                    </div>
                    <div class="st-item">
                        <div class="b_stat">
                            <p class="reiting_num"><?php 
                            if($orders_list) echo count($orders_list);
                            else echo("0"); ?>                          
                            </p>
                            <p class="reiting_text">Заказы</p>
                        </div>
                    </div>
                    <div class="st-item">
                        <div class="b_stat">
                            <p class="reiting_num"><?php 
                            if($reviews) echo count($reviews);
                            else echo "0";
                                ?>
                            </p>
                            <p class="reiting_text">Отзывы</p>
                        </div>
                    </div>
                </div>
            <?php    
            }elseif($identity['isconfirm']== -1) {
            // Предупреждение об ошибках в документах ?> 
                <div class="check_need">
                    <div class="order_content__subtitle">Проверка данных</div>
                    <div class="text text_danger"><?= Yii::$app->params['docs_error'] ?></div>               
                </div>
            <?php      
            }elseif( $identity['isnewdocs'] == 0){ 
            // Предупреждение о проверке документов ?>  
                <div class="check_need">
                    <div class="order_content__subtitle">Проверка данных</div>
                    <div class="text"><?= Yii::$app->params['docs_check_1'] ?></div>
                    <div class="text text_moon"><?= Yii::$app->params['docs_check_2'] ?>
                        <a href="<?=url::to('/doc/update')?>">Пройти проверку</a>
                    </div>
                </div>
            <?php } 
            elseif ( $identity['isnewdocs'] == 1){ 
            // Предупреждение о документf[  на проверке] ?> 
                <div class="check_need">
                    <div class="order_content__subtitle">Проверка данных</div>
                    <div class="text"><?= Yii::$app->params['docs_checking_1'] ?></div>
                    <div class="text text_moon"><?= Yii::$app->params['docs_checking_2'] ?></div>
                </div>
            <?php } ?>    
                
            <div class="order_content__subtitle">Абонементы
                <a href="<?=Url::to(['/cabinet/abonement-choose']) ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>        
            <div class="text">Приобретите или посмотрите информацию о <br>действующих абонементах</div>
        <?php  } ?>

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
            <!-- <a href="/cabinet/<?php
                //if($identity['isexec']) echo"exec-card?id=".$identity['id']; 
                //else echo"user-card?id=".$identity['id'];?>" class="text text__anketa">Просмотр анкеты</a> -->
                <a href="/cabinet/user-card?id=<?= $identity['id'] ?>" class="text text__anketa">Просмотр анкеты</a>
        </div>    
            
         
        <div class="order_buttons">
            <a href="/page/logout" class="register active">Выйти из профиля</a>            
        </div> 
        

    </div>        
</div>