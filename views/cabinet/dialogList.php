<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

//debug($dialog_list );

$order_id = $dialog_list[0]['chat']['order_id'] ;
$exec_id  = $dialog_list[0]['chat']['exec_id'] ;
$customer_id = $dialog_list[0]['chat']['customer_id'] ;
$work_form_name = $_GET['work_form_name'];
$user_id = Yii::$app->user->identity->id;
//echo("order_id".$order_id);
//debug($exec_id);
?>
<?php //Pjax::begin(); ?>

<div class="wrapper__addorder wrapper__addorder__card">

    <!-- шапка -->
    <div class="b_header b_header__tuning __dialog">
        <div class="b_avatar b_avatar__tuning  __dialog">          
            <a href="<?=Url::to(['/cabinet/user-card','id'=>$user2['id'] ]) ?>">
                <img src="<?= user_photo($user2['avatar'])?>" alt="">
            </a>
        </div>

        <div class="b_text b_text__tuning __dialog">
            <!-- <span class="fio"><?//= $work_form_name ?> - <?//= $dialog_list[0]['user']['username']?></span> -->
            <span class="fio"><?= $work_form_name ?> - <?= $user2['username']?></span>
            <?php 
            if($is_show_exec) {  
                if ( $dialog_list[0]['user']['isconfirm']==1){ //Если Исполнитель и профиль проверен-показываем?>     
                    <div class="checked">Профиль проверен</div>             
                <?php   } 
                else echo("Профиль не проверен");
            }    ?>
            
            <div class="visited">Был в сети <?php
                    $tfrom = time_from($max_date['update_time']);
                    if($tfrom['days'] > 0) echo $tfrom['days']." дн. назад"; 
                    elseif($tfrom['hours'] > 0) echo $tfrom['hours']." час. назад";
                    else echo $tfrom['minutes']." мин. назад" 
                    ?>                                    
            </div>
        </div>
       
        <div class="addition">
            <?php
            if($is_sghow_exec) { ?>    
                <p><?php           
                    $ucarr=array(); // для неповторяющиеся категории услуг
                    $price=array(); // для определения мин цены
                    foreach($user_category as $uc){
                        // собираем неповторяющиеся категории услуг в массив
                        if (!in_array($uc['category']['name'],$ucarr)) $ucarr[]=$uc['category']['name'];
                        $price[]=$uc['price_from'];
                    }    
                    foreach($ucarr as $uca) {echo $uca." ";}
                    $minprice= min($price);
                ?>                
                </p>                
                <p class='price_from'>от <?= $minprice ?> ₽</p>
            <?php } ?>

            <a href='<?= Url::to(['/cabinet/user-card','id'=>$user2['id'] ]) ?>' class="white_btn">
                <?php if($user_id==$dialog_list[0]['chat']['exec_id']) echo"Перейти к заказчику";
                        else echo"Перейти к исполнителю"?>
            </a>
        </div>                   
    </div>    

    <!-- заголовок -->
    <?php Pjax::begin(['timeout' => false ]); ?>

    <div class="order_content order_content__tuning __dialog">
        <div class="date-now"><?= date('d.m.Y')?></div>
        <div class="dialog_rules">Правила переписки<br>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iure blanditiis consectetur quaerat dolor repellat possimus molestiae, accusamus ullam pariatur in, consequuntur voluptatibus obcaecati delectus, ratione error maxime neque! Vero, pariatur.
        </div>
        <div class="order_inf">
            <div class="order_num">Заказ №<?= $order['id']?>.  Размещен - <?php  $dt=convert_datetime_en_ru($order['added_time']);
                    echo $dt['HidmY'] ?>
            </div>
            <div class="who_need">Требуется: <?= $order['who_need']?></div>
            <div class="details">Детали: <?= $order['details']?></div>
            <div class="wishes">Пожелания: <?= $order['wishes']?></div>
        </div>

    <!-- диалог -->    
        <?php 
        foreach($dialog_list as $message){ 
            if ($message['user_id'] == Yii::$app->user->identity->id) {// сообщение текущего юзера?>
                <div class="my_message__dialog">
                    <p><?= $message['message'];?></p>
                    <div class='message_time message_time__my'>Отправлено: <?php  $dt=convert_datetime_en_ru($message['send_time']);
                    echo $dt['HidmY'] ?></div>
                    <div class="isread"><?php if(!$message['new']) echo"Прочитано"?></div>

                </div>
            <?php }else{ // сообщение партнера ?>
                <div class="message__dialog">
                   <p> <?= $message['message'];?></p>   
                   <p class='message_time'><?php  $dt=convert_datetime_en_ru($message['send_time']);
                    echo $dt['HidmY'] ?></p>
                </div>
            <?php  }
        }  ?>

    <!-- если чат закрыт - окно ввода сообщения и кнопки не показываем -->
        <?php 
        if($dialog_list['chat']['chat_status']) {?>
           
            <!-- ввод сообщения     -->
                <?php         
                if($order_exec['result']<>1 && $order_exec['result']<>0 || Empty($order_exec['result'])) 
                {   // исполнитель не завершил заказ - диалог открыт
                    $form = ActiveForm::begin([
                        'id' => 'message-form',
                        'options' => [
                            'data-pjax' => true,
                            ],
                        ]); ?>

                        <div class="center">
                            <input type="text" class="message_text" name="message" placeholder="Введите сообщение">
                                        
                            <div class="form-group send-message">
                                <input type="submit" name="ok" value="" class = 'message-button', 
                                    name = 'message-button', id="message-button" title="Отправить сообщение"/>             
                            </div>
                        </div>
                <?php ActiveForm::end(); 
                }?>

            <!-- вывод flesh - сообщения -->


            <!-- кнопки для Заказчика -->
            <?php if($user_id==$dialog_list[0]['chat']['customer_id']) 
            { ?>

                <div class="buttons__dialog">
                    <?php  
                    // echo"status=";
                    // debug($status,0);  ?>

                    <!-- Кнопка показать контакты исполнителя -->
                    <?php            
                    if ($_GET['status']=='Диалог открыт' ) { 
                        if(!$ischoose){   ?> 
                           <a href="#!" class="contacts">Показать контакты</a>           
                        <?php } ?>
                    <?php } ?>

                    <!-- модальное окно - выбрать исполнителя -->
                    <?php if($_GET['status']=='Диалог открыт') { ?>
                        
                            <?php
                            if(!$ischoose) {      
                                // исполнитель НЕ выбран
                                // модальное окно - Выбрать исполнителем                  
                                    Modal::begin([
                                        'header' => '<h2>Выбрать исполителем</h2><h3>(зафиксировать результаты переговоров)<h3>',
                                        'id' => "win_choose",   
                                        'toggleButton' => [
                                            'label' => 'Выбрать исполнителем',
                                            'tag' => "a",
                                            'class' => 'choose',
                                            'id' => 'modal_choose',
                                         ],                     
                                    ]);
                                ?>   
                                        
                                <div class="OrderExec">                

                                        <?php $form = ActiveForm::begin([
                                            'id' => 'choose-form',
                                            'options' => [
                                                'data-pjax' => true,                           
                                                ],
                                            ]); ?>

                                            <input hidden type="text" name="field_name" value="choose">
                                       
                                            <?//= $form->field($order_exec, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>
                                            <?//= $form->field($order_exec, 'exec_id')->hiddenInput(['value' => $exec_id])->label(false) ?>
                                            <?= $form->field($order_exec_form, 'price') ?>
                                            <?= $form->field($order_exec_form, 'prepayment_summ') ?>
                                            
                                            <!-- Безопасная сделка -->
                                            <div class="form-group exactly">                    
                                                <div class="control-label choose__safe">Безопасная сделка <span>Selfevent</span></div>                           
                                                <div class="toggle-button-cover"> 
                                                      <div class="button-cover">
                                                        <div class="button r" id="button-1">
                                                          <input type="checkbox" class="checkbox tuning" name= 'OrderExecForm[safe_deal]' id="orderexec-safe_deal" 
                                                          <?php if ($order_exec_form['safe_deal']) echo 'checked';?>>
                                                          <div class="knobs"></div>
                                                          <div class="layer"></div>
                                                        </div>
                                                      </div>
                                                </div>
                                            </div>

                                            <div class="b_balance">
                                                <div class="control-label choose__safe">Ваш баланс</div>
                                                <div class="balance">10000 ₽</div>  
                                            </div>
                                        
                                            <div class="choose_buttons">
                                                <?= Html::submitButton('Оплатить', ['class' => 'register active']) ?>
                                            </div>                                 

                                        <?php ActiveForm::end(); ?>
                                </div><!-- OrderExec -->               

                                <?php 
                                Modal::end();                   
                                // модальное окно - Выбрать исполнителем -Конец   
                                ?>                
                              
                                <?php //Pjax::end(); 
                                // <!--Выбрать исполнителем -Конец       -->
                            } ?>                         
                    <?php } ?>

                    <!-- модальное окно - подтвердить выполнение   -->
                    <?php if($_GET['status']=='Принят к исполнению' || $status=='Принят к исполнению') { ?>
                        
                            <?php 
                            if($ischoose && !$order_exec['exec_cancel']){ ?>
                                <a href="/cabinet/dialog-list?chat_id=<?=$dialog_list[0]['chat_id'] ?>&work_form_name=<?= $work_form_name ?>&confirm=order_confirm" title="Подтвердить выполнение" class='choose'>Подтвердить выполнение</a>
                            <?php } ?>              
                    <?php } ?>

                    <?php if($_GET['status']=='Отказ Заказчика') { ?>

                    <?php } ?>
                         
                    <!-- модальное окно - Отказать  исполнителю  -->                            
                    <?php
                    if($_GET['status']=='Диалог открыт' || 
                        $_GET['status']=='Принят к исполнению' || $status=='Принят к исполнению' ||
                        $_GET['status']=='Исполнитель выбран' || $status=='Исполнитель выбран') {
                        Modal::begin([
                            'header' => '<h2>Отказ от исполнителя</h2>',
                            'id' => "win_cancel_exec",   
                            'toggleButton' => [
                                'label' => 'Отказаться',
                                'tag' => "a",
                                'class' => 'choose',
                                'id' => 'modal_cancel_exec',
                             ],                     
                        ]);
                        ?>

                            <div class="cancel_subtitle">Вы действительно хотите отказать исполнителю в заказе?   
                            </div>
                            <div class="center">Это действие невозможно изменить и вы не сможете общаться с исполнителем по текущему заказу. Предоплату исполнитель вправе не возвращать!</div>
                            <?php 
                            $form = ActiveForm::begin([
                                    'id' => 'cancel-form',
                                    'options' => [
                                        'data-pjax' => true,                           
                                        ],
                                    ]); ?>

                            <input hidden type="text" name="field_name" value="win_cancel_exec">

                            <?//= $form->field($order_exec, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>
                            <?//= $form->field($order_exec, 'exec_id')->hiddenInput(['value' => $exec_id])->label(false) ?>

                            <div class="choose_buttons">
                                <?= Html::submitButton('Отказать', ['class' => 'register active']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?php 
                        Modal::end();                   
                        // модальное окно - Отказать  исполнителю -Конец   
                    }?>           
                    
                    <!-- модальное окно - пожаловаться на Исполнителя  -->
                    <?php 
                    if($iscomplain) goto met_review_cust; // есть жалоба - кнопку не рисуем
                    if( $_GET['status']=='Диалог открыт'     || 
                        $_GET['status']=='Отказ Заказчика'   ||  $status=='Отказ Заказчика'   ||
                        $_GET['status']=='Отказ Исполнителя' ||  $status=='Отказ Исполнителя' ||
                        $_GET['status']=='Исполнитель выбран' || $status=='Исполнитель выбран'){             
                        Modal::begin([
                                'header' => '<h2>Жалоба на исполнителя</h2>',
                                'id' => "win_complain",   
                                'toggleButton' => [
                                    'label' => 'Пожаловаться',
                                    'tag' => "a",
                                    'class' => 'choose',
                                    'id' => 'modal_complain',
                                 ],                     
                        ]);
                        ?>    
                            <div class="complain">                

                                <?php $form = ActiveForm::begin([
                                    'id' => 'complain-form',
                                    'options' => [
                                        'data-pjax' => true,                           
                                        ],
                                    ]); ?>

                                    <input hidden type="text" name="field_name" value="complain">
                                    <?= $form->field($complain, 'from_user_id')->hiddenInput(['value' => $user_id])->label(false) ?>
                                    <?= $form->field($complain, 'for_user_id')->hiddenInput(['value' => $exec_id])->label(false) ?>
                                    <?= $form->field($complain, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>
                                    <?= $form->field($complain, 'complain')->textarea(['style'=>'resize:vertical', 'rows'=>'5']); ?>
                    
                                                                                 
                                    <div class="choose_buttons">
                                        <?= Html::submitButton('Отправить', ['class' => 'register active']) ?>
                                    </div>                                 

                                <?php ActiveForm::end(); ?>
                            </div>               
                        <?php 
                        Modal::end();               
                        ?>
                    <?php } ?> 

                    <!-- Кнопка Оставить отзыв на Исполнителя -->
                    <?php 
                    met_review_cust:
                    if($isreview) goto met_close_cust;  // если есть отзыв - не рисуем кнопку
                    if( $_GET['status']=='Отказ Исполнителя'|| $status=='Отказ Исполнителя' || 
                        $_GET['status']=='Заказ выполнен'   || $status=='Заказ выполнен' || 
                        $_GET['status']=='Отказ Заказчика'  || $status=='Отказ Заказчика') { ?>
                    
                    <a href="/cabinet/user-review?for_user_id=<?= $exec_id ?>&chat_id=<?= $dialog_list[0]['chat_id']?>&order_id=<?=$order_id?>" class='choose' title="Оставить отзыв" >Оставить отзыв</a>
                   
                    <?php } ?>

                    <!-- Кнопка Закрыть чат   -->                  
                    <?php 
                    met_close_cust:
                    if( $_GET['status']=='Отказ Исполнителя' || $status=='Отказ Исполнителя' ||
                        $_GET['status']=='Заказ выполнен'    || $status=='Заказ выполнен') { ?>      
                        <a href="dialog-list?chat_id=<?= $dialog_list[0]['chat_id']?>&work_form_name=cancel" class="choose">Закрыть чат</a>
                    <?php } ?>

                </div>
            <?php 
            }else{  ?>    
            <!-- кнопки для Заказчика Конец-->

            <!-- кнопки для Исполнителя -->
                <div class="buttons__dialog" id='buttons__dialog'>
                
                <!-- Кнопка Принять к исполнению -->
                    <?php  
                    if( $_GET['status']=='Исполнитель выбран' || $status=='Исполнитель выбран') { ?>              
                        <a href="<?= URL::to(['dialog-list','chat_id'=>$dialog_list[0]['chat_id'], 'work_form_name'=>"accepted"]) ?>"  class="choose",>Принять к исполнению</a>
                    <?php }?> 

                <!-- модальное окно - Отказать  Заказчику (от заказа) -->
                    <?php 
                    if( $_GET['status']=='Диалог открыт'       ||
                        $_GET['status']=='Принят к исполнению' || $status=='Принят к исполнению') {
                                         
                        Modal::begin([
                            'header' => '<h2>Отказ от заказа</h2>',
                            'id' => "win_cancel_order",   
                            'toggleButton' => [
                                'label' => 'Отказаться',
                                'tag' => "a",
                                'class' => 'choose',
                                'id' => 'modal_cancel_order',
                             ],                     
                        ]);
                        ?>
                            <div class="cancel_subtitle">Вы действительно хотите отказаться от заказа?   
                            </div>
                            <div class="center">Предоплата (если была) будет возвращена заказчику. Диалог будет закрыт!</div>
                            <?php 
                            $form = ActiveForm::begin([
                                    'id' => 'cancel-order-form',
                                    'options' => [
                                        'data-pjax' => true,                           
                                        ],
                                    ]); ?>
                                <input hidden type="text" name="field_name" value="win_cancel_order">
                               <!--  <input hidden type="text" name="OrderExec[order_id]" value="<?=$order_id?>">
                                <input hidden type="text" name="OrderExec[exec_id]" value="<?=$exec_id?>"> -->
                               
                                <div class="choose_buttons">
                                    <?= Html::submitButton('Отказаться', ['class' => 'register active']) ?>
                                </div>
                            <?php ActiveForm::end(); ?>
                        <?php 
                        Modal::end();                   
                        // модальное окно - Отказаться от заказа-Конец   
                    }    ?>                   
                        
                <!-- модальное окно - Пожаловаться на Заказчика  -->
                    <?php 
                    // echo"iscomplain=";
                    // debug($iscomplain);
                    if($iscomplain) goto met_review; // есть жалоба - кнопку не рисуем
                    if( $_GET['status']=='Диалог открыт'    || $status == 'Диалог открыт'   ||
                        $_GET['status']=='Отказ Заказчика'  || $status == 'Отказ Заказчика' ||
                        $_GET['status']=='Отказ Исполнителя'|| $status == 'Отказ Исполнителя'||
                        $_GET['status']=='Принят к исполнению' || $status=='Принят к исполнению') {                      
                        // модальное окно - Пожаловаться на Заказчика                  
                        Modal::begin([
                                'header' => '<h2>Жалоба на заказчика</h2>',
                                'id' => "win_complain_cust",   
                                'toggleButton' => [
                                    'label' => 'Пожаловаться',
                                    'tag' => "a",
                                    'class' => 'choose',
                                    'id' => 'modal_complain_cust',
                                 ],                     
                        ]);
                        ?>   
                                
                        <div class="complain">                

                            <?php $form = ActiveForm::begin([
                                'id' => 'complain-cust-form',
                                'options' => [
                                    'data-pjax' => true,                           
                                    ],
                                ]); ?>

                                <input hidden type="text" name="field_name" value="complain">
                                <?= $form->field($complain, 'from_user_id')->hiddenInput(['value' => $user_id])->label(false) ?>
                                <?= $form->field($complain, 'for_user_id')->hiddenInput(['value' => $customer_id])->label(false) ?>
                                <?= $form->field($complain, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>
                                <?= $form->field($complain, 'complain')->textarea(['style'=>'resize:vertical', 'rows'=>'5']); ?>
                
                                                                             
                                <div class="choose_buttons">
                                    <?= Html::submitButton('Отправить', ['class' => 'register active']) ?>
                                </div>                                 

                            <?php ActiveForm::end(); ?>
                        </div>               

                        <?php 
                        Modal::end();               
                    } ?>

                <!-- Кнопка Оставить отзыв      -->
                <?php 
                met_review:
                if($isreview) goto met_close;  // если есть отзыв - не рисуем кнопку
                    If( $_GET['status']=='Отказ Исполнителя'|| $status=='Отказ Исполнителя' || 
                        $_GET['status']=='Заказ выполнен'   || $status=='Заказ выполнен' || 
                        $_GET['status']=='Отказ Заказчика'  || $status=='Отказ Заказчика') { ?>
                    
                    <a href="/cabinet/user-review?for_user_id=<?= $customer_id ?>&chat_id=<?= $dialog_list[0]['chat_id']?>&order_id=<?=$order_id?>" class='choose' title="Оставить отзыв">Оставить отзыв</a>
                   
                <?php } ?>
                    
                <!-- Кнопка Закрыть чат   -->                  
                    <?php 
                    met_close:
                    if( $_GET['status']=='Отказ Заказчика' || $status=='Отказ Заказчика' ||
                        $_GET['status']=='Заказ выполнен'  || $status=='Заказ выполнен') { ?>      
                        <a href="dialog-list?chat_id=<?= $dialog_list[0]['chat_id']?>&work_form_name=cancel" class="choose">Закрыть чат</a>
                    <?php } ?>          
                </div>
            <?php } ?>
            <!-- кнопки для Исполнителя Конец-->
        <?php 
        }else{ ?>     
            <div class="chat_title">Этот чат закрыт</div>
        <?php 
        } ?> 

        <?php // закрытие фона модального окна           
            $script = <<< JS
                // Закрытие фона модального окна
                $('.register.active').click(function(e){
                    // отправка формы по pjax и потом удаление фона:        
                    $('.modal-backdrop.fade.in').css('display','none'); 
                    $('body').removeAttr('class');              
                });               
            JS;
            //маркер конца строки, обязательно сразу, без пробелов и табуляции
            $this->registerJs($script, yii\web\View::POS_READY);
        ?>
    </div>
    <?php Pjax::end(); ?>

</div>