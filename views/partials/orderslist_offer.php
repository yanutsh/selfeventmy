<?php
// Формирование Списка отфильтрованных заказов
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
//use app\assets\TemplateAsset;
use app\assets\CabinetAsset;
use kartik\date\DatePicker;
use app\components\page\PageAttributeWidget as PAW;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

//TemplateAsset::register($this);
//CabinetAsset::register($this);

?>
<?php //Pjax::begin(); ?>

<?php
//debug($_SESSION['identity']);
$k=1;
$user_id = Yii::$app->user->id;
//debug($orders_list);
foreach ($orders_list as $ol) 
{ ?>
<?php  // проверяем открыт ли чат по Этому заказу с Этим Исполнителем
    $ref="";
    if(!empty($ol['chats'])){ // есть чаты 
        //debug($ol['chats'],0);
        foreach($ol['chats'] as $ch) {
            //debug($ch);
            if ($ch['customer_id']==$user_id && $ch['exec_id']==$_GET['exec_id']) { 
                $otklic= "По этому заказу чат уже был открыт";
                $ref="hidden";
                break;
            }
        }                              
    } // нет чата 
?>
    <a href="/cabinet/order-offer?exec_id=<?=$_GET['exec_id']?>&order_id=<?= $ol['id']?>" <?=$ref?>>
        <div class="order_item">
            <div class="order_number">Заказ №<?= $ol['id']?></div>
            <div class="order_status <?php
                if ($ol['status_order_id']==2) echo 'color_green';
                elseif ($ol['status_order_id']==4) echo 'color_red';?>">
                <?= $ol['orderStatus']['name'] ?>
            </div>
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
            <div class="order_budget"><?php echo $ol['budget_to'] ?> <span class="rubl">₽</span>
            </div>
            <div class="order_down">
                 <div class="order_city"><?= $ol['orderCity']['name'] ?></div>
                 <div class="order_added"><?= showDate(strtotime($ol['added_time']))?></div>
            </div>
        </div>
    </a>
    
    <?php 
    $k++;
} ?>

<?php //Pjax::end(); ?>   