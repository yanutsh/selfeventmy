<?php
// Формирование Списка отфильтрованных заказов
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\TemplateAsset;
use app\assets\CabinetAsset;
use kartik\date\DatePicker;
use app\components\page\PageAttributeWidget as PAW;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;


TemplateAsset::register($this);
//CabinetAsset::register($this);
require_once('../libs/time_ago.php');
?>

<div class="top100">Исполнители ТОП 100</div>

<?php
foreach ($exec_list as $el) 
{ ?>
    <a href="/cabinet/order-card?id=<?= $el['id'] ?>">
        <!-- <div class="order_item"> -->
        <div class="order_item execlist">
            <!-- <div class="b_header"> -->
                <div class="b_avatar">
                    <img src="/web/uploads/images/users/<?= $el['avatar']?>" alt="">                 
                </div>
               
                <div class="b_text">
                    <span class="fio"><?= $el['username']." - ".$el['workForm']['work_form_name'] ?></span>
                    <p class="title">Услуги: <?= $el['category'][0]['name'] ?></p>
                    <p  class="check"><span>Проверенный сполнитель</span></p>                        
                </div>

                <div class="b_right">
                    <p class="reiting_num">5.0</p>
                    <p class="reitibg_text">Рейтинг</p>
                </div>

                <div class="exec_details">
                    <p>Привет! Меня зовут Ольга! за моими плечами более 100 проведенных свадеб. 
                        Привет! Меня зовут Ольга! за моими плечами более 100 проведенных свадеб. 
                        Привет! Меня зовут Ольга! за моими плечами более 100 проведенных свадеб. </p>
                </div>  

                <!-- <div class="item_footer"> -->
                    <div class="city">Москва</div>
                    <div class="price">10000 ₽</div>
                <!-- </div>   -->
            <!-- </div> -->
        </div>    
    </a> 
<?php
}?>

<div class="top100">Все исполнители</div>

<?php
foreach ($exec_list as $el) 
{ ?>
    <a href="/cabinet/order-card?id=<?= $el['id'] ?>">
        <!-- <div class="order_item"> -->
        <div class="order_item execlist">
            <!-- <div class="b_header"> -->
                <div class="b_avatar">
                    <img src="/web/uploads/images/users/<?= $el['avatar']?>" alt="">                 
                </div>
               
                <div class="b_text">
                    <span class="fio"><?= $el['username']." - ".$el['workForm']['work_form_name'] ?></span>
                    <p class="title">Услуги: <?= $el['category'][0]['name'] ?></p>
                    <p  class="check"><span>Проверенный сполнитель</span></p>                        
                </div>

                <div class="b_right">
                    <p class="reiting_num">5.0</p>
                    <p class="reitibg_text">Рейтинг</p>
                </div>

                <div class="exec_details">
                    <p>Привет! Меня зовут Ольга! за моими плечами более 100 проведенных свадеб. 
                        Привет! Меня зовут Ольга! за моими плечами более 100 проведенных свадеб. 
                        Привет! Меня зовут Ольга! за моими плечами более 100 проведенных свадеб. </p>
                </div>  

                <!-- <div class="item_footer"> -->
                    <div class="city">Москва</div>
                    <div class="price">10000 ₽</div>
                <!-- </div>   -->
            <!-- </div> -->
        </div>    
    </a> 
<?php
}?>