<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

use yii\widgets\Pjax;

//require_once('../libs/user_photo.php');

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

Yii::$app->runAction('cabinet/get-data-from-cache');
$cache = \Yii::$app->cache; 
$work_form = $cache->get('work_form');
$work_form = change_key_new($work_form, 'id');
//debug($work_form);
//debug($chat_list);

?>
<div class="wrapper__addorder wrapper__addorder__card">
    
    <?php  Pjax::begin(['timeout' => false ]); 
    echo $jscr; ?>
    <div class="order_content tabs_header">
        <!-- список вкладок  -->
        <ul class="nav nav-pills">            
            <li id="tab1"><a href="chat-list?tab=1">В работе</a></li>
            <li id="tab2"><a href="chat-list?tab=2">Выполнено</a></li>
            <li id="tab3"><a href="chat-list?tab=3">Недавние</a></li>
            <li id="tab4"><a href="chat-list?tab=4">Архив</a></li>           
        </ul>
    </div>
  
    <div class="order_content">

        <!-- <div class="tab-content"> -->
            <div id="tab-1" class="tab-pane fade in active">                     
                <!-- цикл по чатам В работе-->
                <?php //debug($chat_list) 
                    //debug($msg,0);
                    $user_id = Yii::$app->user->identity->id; 
                ?>
                <!-- Список чатов для Заказчика -->
                <?php                
                if(!Yii::$app->user->identity->isexec){
                    if(!$chat_list) { // нет открытых чатов?>
                        <div class="subtitle">В этой категории чатов нет.</div>
                    <?php }
                    
                    foreach($chat_list as $k=>$chl) { ?>                        
                        <a class="dialog_ref" href="/cabinet/dialog-list?chat_id=<?=$chl['id'] ?>&work_form_name=<?= $work_form[$chl['exec']['work_form_id']]['work_form_name'] ?>" data-pjax="0" title="Перейти к диалогу в этом чате">
                            <div class="order_info chat">
                                <div class="number_info"><span>#<?= substr((string)$chl['order_id'],-2)?></span>
                                    <!-- <div id="badge_chat" class="badge ">15</div> -->
                                    <div class="badge bage_messages"><?= $new_mess_chat[$chl['id']]['kol_new_mess'] ?></div>
                                </div>
                                <div class="text_info">
                                    <div class="full_number">Заказ № <?= $chl['order_id']?></div>
                                    <div class="fio"><?= $work_form[$chl['exec']['work_form_id']]['work_form_name'] ?> <?= $chl['exec']['username'] ?></div>
                                    <div class="last_message">

                                        <?php  
                                        echo end($chl['dialogs'])['message'];
                                        ?>
                                    </div>
                                    <div class="status"><?= $cht_status ?></div> 
                                </div>                    
                            </div>                   
                        </a>
                        <hr>
                    <?php }
                } else { ?>
                <!-- Список чатов для Заказчика Конец-->
                                
                <!-- Список чатов для Исполнителя    --> 
                    <?php
                    //debug($chat_list);

                    if(!$chat_list) { // нет открытых чатов?>
                        <div class="subtitle">В этой категории чатов нет.</div>
                    <?php  }

                    foreach($chat_list as $k=>$chl) { ?>

                        <a class="dialog_ref" href="/cabinet/dialog-list?chat_id=<?=$chl['id'] ?>&work_form_name=<?= $work_form[$chl['customer']['work_form_id']]['work_form_name'] ?>" data-pjax="0" title="Перейти к диалогу в этом чате">

                            <div class="order_info chat">
                                <div class="number_info"><span>#<?= substr((string)$chl['order_id'],-2)?></span>
                                    <!-- <div id="badge_chat" class="badge ">15</div> -->
                                    <div class="badge bage_messages"><?= $new_mess_chat[$chl['id']]['kol_new_mess'] ?></div>
                                </div>
                                <div class="text_info">
                                    <div class="full_number">Заказ № <?= $chl['order_id']?></div>
                                    <div class="fio"><?= $work_form[$chl['customer']['work_form_id']]['work_form_name'] ?> <?= $chl['customer']['username'] ?></div>
                                    <div class="last_message">
                                        <?php echo end($chl['dialogs'])['message']; ?>
                                    </div>
                                </div>                    
                            </div>                   
                        </a>
                        <hr>
                    <?php }
                }?>
                <!-- Список чатов для Исполнителя Конец-->
            </div>            
        <!-- </div> -->
        <?php  //Pjax::end(); ?> 

    </div>
        <?php     
        $script = <<< JS
            console.log(tab);
            $('#tab'+tab).addClass('active');            
        JS;
        //маркер конца строки, обязательно сразу, без пробелов и табуляции
        $this->registerJs($script, yii\web\View::POS_READY);
        ?>          
     <?php  Pjax::end(); ?>    
</div>