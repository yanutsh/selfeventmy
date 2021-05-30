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
//require_once('../libs/time_ago.php');
//require_once('../libs/user_photo.php');

//debug($model['only_top']);
?>

<div class="top100">Исполнители ТОП 100</div>

<?php
//debug($min_price);

$city_new = change_key_new($city, 'id');
//debug($city_new);    
$min_price_new = change_key_new($min_price, 'user_id');
//debug($min_price_new);   

foreach ($exec_list as $el) 
{   //debug($el,0);
    ?>
    <a class="block" href="/cabinet/user-card?id=<?= $el['id'] ?>">
        <!-- <div class="order_item"> -->
        <div class="order_item execlist">
            <!-- <div class="b_header"> -->
                <div class="b_avatar">
                    <img src="<?= user_photo($el['avatar'])?>" alt="">                 
                </div>
               
                <div class="b_text">
                    <span class="fio"><?= $el['username']." - ".$el['workForm']['work_form_name'] ?></span>
                    <p class="title">Услуги: <?= $el['category'][0]['name'] ?></p>
                    <p  class="check"><span>Проверенный сполнитель</span></p>                        
                </div>

                <div class="b_right">
                    <p class="reiting_num"><?= $el['reyting']?></p>
                    <p class="reitibg_text">Рейтинг</p>
                </div>

                <div class="exec_details">
                    <p><?= $el['myself']?> </p>
                </div>  

                <!-- <div class="item_footer"> -->
                    <div class="city">                        
                        <?php 
                        $cities=""; 
                        foreach($el['userCities'] as $id=>$uc) {
                            if( $cities=="") $cities = $city_new[$uc['city_id']]['name'];
                            else $cities .= ", ".$city_new[$uc['city_id']]['name'];          
                        } 
                        echo $cities; ?>
                    </div>
                    <div class="price">от <?=
                        $min_price_new[$el['id']]['min_price_from']; ?> ₽
                    </div>
                <!-- </div>   -->
            <!-- </div> -->
        </div>    
    </a> 
<?php
}?>


<div class="top100__all" style="display:
        <?php if (isset($model['only_top'])) echo "none"; 
              else echo "block"?>">
    <div class="top100">Все исполнители</div>
<?php
    foreach ($exec_list as $el) 
    {   //debug ($el); ?>
        <a href="/cabinet/user-card?id=<?= $el['id'] ?>">
            <!-- <div class="order_item"> -->
            <div class="order_item execlist">
                <!-- <div class="b_header"> -->
                    <div class="b_avatar">
                        <img src="<?= user_photo($el['avatar'])?>" alt="">                 
                    </div>
                   
                    <div class="b_text">
                        <span class="fio"><?= $el['username']." - ".$el['workForm']['work_form_name'] ?></span>
                        <p class="title">Услуги: <?= $el['category'][0]['name'] ?></p>
                        <p  class="check"><span>Проверенный сполнитель</span></p>                        
                    </div>

                    <div class="b_right">
                        <p class="reiting_num"><?= $el['reyting']?></p>
                        <p class="reitibg_text">Рейтинг</p>
                    </div>

                    <div class="exec_details">
                        <p><?= $el['myself']?></p>
                    </div>  

                    <!-- <div class="item_footer"> -->
                        <div class="city">                        
                        <?php 
                        $cities=""; 
                        foreach($el['userCities'] as $id=>$uc) {
                            if( $cities=="") $cities = $city_new[$uc['city_id']]['name'];
                            else $cities .= ", ".$city_new[$uc['city_id']]['name'];          
                        } 
                        echo $cities; ?>
                    </div>
                    <div class="price">от <?=
                        $min_price_new[$el['id']]['min_price_from']; ?> ₽
                    </div>
                    <!-- </div>   -->
                <!-- </div> -->
            </div>    
        </a> 
    <?php
    }?>
</div>    