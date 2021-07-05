<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;
use app\models\Sex;
use app\models\Category;
use app\models\City;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

// Получение исходных данных для формы
$category = Category::find() ->orderBy('name')->asArray()->all();
$city = City::find() ->orderBy('name')->all();
?>

<div class="wrapper__addorder wrapper__addorder__card">
    <div class="b_header">
        <div class="b_avatar">
            <a href="/cabinet/user-card?id=<?=$order['user']['id']?>">
                <img src="<?= user_photo($order['user']['avatar'])?>" alt="">
            </a>
        </div>
       <!--  <div class="clearfix"></div> -->
        <div class="b_text">
            <span class="fio"><?= $order['workForm']['work_form_name']." ".$order['user']['username'] ?></span>

            <p  class="check">
                <span>
                <?php if($order['user']['isexec']) echo"Профиль проверен"?>
                </span>
            </p>
            
            <p><span>в сети - 
                <?php
                    $tfrom = time_from($max_date['update_time']);
                    if($tfrom['days'] > 0) echo $tfrom['days']." дн. назад"; 
                    elseif($tfrom['hours'] > 0) echo $tfrom['hours']." час. назад";
                    else echo $tfrom['minutes']." мин. назад" 
                    ?>
            </span><p>        

        </div>
        <div class="b_right">
            <p  class="order_num"><span>Заказ № <?= $order['id'] ?></span></p>

            <?php if($order['user']['isexec']) { ?>
            <div class="letter">
                <a href="mailto:<?= $order['user']['email']?>" target="_blank">
                    <img src="/web/uploads/images/envelope.png" alt="Написать">
                    <div><span>Написать</span></div>
                </a>
            </div> 
            <?php } ?>

        </div>   
    </div>

    <div class="order_content">
        <div class="order_content__title">
            <?php $name="";
            foreach($order['category'] as $cat){
                if ($name=="") $name = $cat['name'];
                else $name .= ", ".$cat['name'];                 
            } echo $name; ?>        
        </div>
        <div class="order_content__budget"><?= $order['budget_to']?> ₽.</div>
        <div class="order_content__declare">Описание события</div>
        <p><?= $order['details']?></p>

        <div class="slider">
            <?php foreach($order['orderPhotos'] as $photo){ ?>}
                <div>
                    <a href="/web/uploads/images/orders/<?= $photo['photo']?>" class="highslide" onclick="return hs.expand(this)" >
                        <img src="/web/uploads/images/orders/<?= $photo['photo']?>" alt="">
                    </a>
                </div>
            <?php } ?>
        </div>

        <div class="order_content__subtitle">Город</div>
        <div class="text"><?= $order['orderCity']['name']?></div>
        <div class="order_content__subtitle">Когда</div>
        <div class="text"><?= convert_datetime_en_ru($order['date_from']) ?> (<?= convert_datetime_en_ru($order['added_time'])['w']?>) в 13:00???</div>

        <?php if($order['user']['isexec']) { ?>
            <div class="order_content__subtitle">Цена отклика</div>
            <div class="text">
                <span class="cancel">50 рублей</span>
                <span class="blank">Бесплатно</span>
            </div>
        <?php } ?>    

        <div class="order_content__subtitle">Детали заказа</div>
        <div class="text">Заказ опубликован <?php
            $d = convert_datetime_en_ru($order['added_time']);
            echo $d['dMruY']; ?> в <?= convert_datetime_en_ru($order['added_time'])['Hi']?></div>

        <?php if($order['user']['isexec']) { ?>    
            <div class="reiting">
                <img src="/web/uploads/images/attention_24px.png" alt="Внимание">
                <div class="blank"><span>Ваше предложение будет N-м в рейтинге заказа</span></div>
            </div>

            <div class="abonement active">
                <div>
                    <div class="abonement__text active"><span>Активен абонемент на безлимитные отклики</span></div>
                    <div class="abonement__text"><span>Приобретите абонемент на 6 месяцев и отправляйте отклики бесплатно</span></div>
                    <img src="/web/uploads/images/abonement_24px.png" alt="абонемент">
                </div>
            </div>

            <div class="reiting cry">
                <img src="/web/uploads/images/attention_24px.png" alt="Внимание">
                <div class="blank"><span>Пожаловаться на заказ</span></div>
            </div> 

            <div class="order_buttons">
                <!-- <div> -->
                    <a href="/cabinet/index" class="register">Не интересно</a>
                <!-- </div> -->
                <!-- <div> -->
                    <a href="" class="register active">Откликнуться</a>
                <!-- </div> -->
            </div>
        <?php } ?>   

    </div>        
</div>