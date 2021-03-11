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

TemplateAsset::register($this);
//CabinetAsset::register($this);
require_once('../libs/time_ago.php');
?>

<?php

foreach( $orders_list as $ol){ ?>
    <div class="order_item">
        <div class="order_status <?php
            if ($ol['status_order_id']==2) echo 'color_green';
            elseif ($ol['status_order_id']==4) echo 'color_red';
                                 ?>
        "><?= $ol['orderStatus']['name'] ?></div>
        <div class="order_category">
            <?php 
            $category_names = "";
            foreach ($ol['category'] as $cat){
                if ( $category_names=="" ) $category_names = $cat['name']; 
                else $category_names .= ", ".$cat['name'];                
            } 
            echo $category_names;
            ?>
                
        </div>
        <div class="order_details"><?= $ol['details'] ?> </div>
        <div class="order_budget"><?php echo $ol['budget_from'] ?> <span class="rubl">₽</span>
        </div>
        <div class="order_down">
            <div class="order_city"><?= $ol['orderCity']['name'] ?></div>
            <div class="order_added"><?= showDate(strtotime($ol['added_time']))?></div>
        </div>
    </div>      
<?php  }; ?>