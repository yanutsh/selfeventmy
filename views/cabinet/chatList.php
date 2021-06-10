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
    

    <div class="order_content tabs_header">
        <!-- список вкладок  -->
        <ul class="nav nav-pills">
            <li class="active"><a href="#tab-1" data-toggle="tab">Недавние</a></li>
            <li><a href="#tab-2" data-toggle="tab">В работе</a></li>
            <li><a href="#tab-3" data-toggle="tab">Выполнено</a></li>
            <li><a href="#tab-4" data-toggle="tab">Архив</a></li>
        </ul>
    </div>

    <div class="order_content">

        <?php  //Pjax::begin(); ?>
        <h3> Тест <?= $msg ?></h3>
        <a href="/cabinet/chat-list?var=first">Тест Pjax</a> 

        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade in active">
                <!-- цикл по Недавним чатам -->
                <?php //debug($chat_list) 
                      //debug($new_mess_chat);
                    $user_id = Yii::$app->user->identity->id; 
                ?>
                <!-- Список чатов для Заказчика -->
                <?php                
                if(!Yii::$app->user->identity->isexec){
                    foreach($chat_list as $k=>$chl) { ?>
                    <a class="dialog_ref" href="/cabinet/dialog-list?chat_id=<?=$chl['id'] ?>&work_form_name=<?= $work_form[$chl['exec']['work_form_id']]['work_form_name'] ?>" title="Перейти к диалогу в этом чате">
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
                                    // foreach($chl['dialogs'] as $chd) {
                                    //     if ($chd['new'] && ($chd['user_id']<>$user_id) ) {
                                    //         echo $chd['message'];
                                    //         break;
                                    //     }
                                    // }?>
                                </div>
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
    
                    foreach($chat_list as $k=>$chl) { ?>
                    <a class="dialog_ref" href="/cabinet/dialog-list?chat_id=<?=$chl['id'] ?>&work_form_name=<?= $work_form[$chl['customer']['work_form_id']]['work_form_name'] ?>" title="Перейти к диалогу в этом чате">
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
                <!-- Список чатов для Исполнителяец Кон-->
            </div>

            <div id="tab-2" class="tab-pane fade">
                <!-- цикл по открытым Чатам  -->
                <div class="order_info chat">
                    <div class="number_info"><span>#22</span></div>
                    <div class="text_info">
                        
                    </div>
                </div>                
            </div>

            <div id="tab-3" class="tab-pane fade">
               <!-- цикл по закрытым Чатам  -->
                <div class="order_info chat">
                    <div class="number_info"><span>#33</span></div>
                    <div class="text_info">
                        
                    </div>
                </div>                
            </div>

            <div id="tab-4" class="tab-pane fade">
               <!-- цикл по архивным Чатам  -->
                <div class="order_info chat">
                    <div class="number_info"><span>#44</span></div>
                    <div class="text_info">
                        
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <?php  //Pjax::end(); ?>        
</div>