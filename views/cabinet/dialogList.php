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
//debug($user_category); 
//debug($order_exec); 
$order_id = $dialog_list[0]['chat']['order_id'] ;
$exec_id  = $dialog_list[0]['chat']['exec_id'] ;
//echo("order_id".$order_id);
//debug($exec_id);
?>
<?php //Pjax::begin(); ?>

<div class="wrapper__addorder wrapper__addorder__card">

    <div class="b_header b_header__tuning __dialog">
        <div class="b_avatar b_avatar__tuning  __dialog">          
            <a href="<?=Url::to(['/cabinet/user-card','id'=>$user2['id'] ]) ?>">
                <img src="<?= user_photo($user2['avatar'])?>" alt="">
            </a>
        </div>

        <div class="b_text b_text__tuning __dialog">
            <!-- <span class="fio"><?= $work_form_name ?> - <?= $dialog_list[0]['user']['username']?></span> -->
            <span class="fio"><?= $work_form_name ?> - <?= $user2['username']?></span>
            <?php 
            if($isexec) {  
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
                if($isexec) { ?>    
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

                <a href='<?= Url::to(['/cabinet/user-card','id'=>$user2['id'] ]) ?>' class="white_btn">Перейти к исполнителю</a>
            </div> 
                  
    </div>    

    <?php Pjax::begin(); ?>
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
        
        <?php $form = ActiveForm::begin([
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

        <?php ActiveForm::end(); ?>


        <!-- вывод flesh - сообщения об ошибках-->
            <div class="flash_choose">
            <?php if( Yii::$app->session->hasFlash('payment_ok') ): ?>
                 <div class="alert alert-danger alert-dismissible choose" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <?php echo Yii::$app->session->getFlash('payment_ok'); ?>
                 </div>
            <?php endif;?>
            </div>

        <div class="buttons__dialog">
            <!-- модальное окно - показать контакты исполнителя -->
            <?php 
                if(!$ischoose){   ?> 
                    <a href="#!" class="contacts">Показать контакты</a>           
                <?php } ?>
            <!-- модальное окно - выбрать исполнителя -->
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
                           
                                <?= $form->field($order_exec, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>
                                <?= $form->field($order_exec, 'exec_id')->hiddenInput(['value' => $exec_id])->label(false) ?>
                                <?= $form->field($order_exec, 'price') ?>
                                <?= $form->field($order_exec, 'prepayment_summ') ?>
                                <?//= $form->field($order_exec, 'safe_deal') ?>
                                
                                <!-- Безопасная сделка -->
                                <div class="form-group exactly">                    
                                    <div class="control-label choose__safe">Безопасная сделка <span>Selfevent</span></div>                           
                                    <div class="toggle-button-cover"> 
                                          <div class="button-cover">
                                            <div class="button r" id="button-1">
                                              <input type="checkbox" class="checkbox tuning" name= 'OrderExec[safe_deal]' id="orderexec-safe_deal" 
                                              <?php if ($order_exec['safe_deal']) echo 'checked';?>>
                                              <div class="knobs"></div>
                                              <div class="layer"></div>
                                            </div>
                                          </div>
                                    </div>
                                </div>

                                <div class="b_balance">
                                    <div class="control-label choose__safe">Ваш баланс</div>
                                    <div class="balance">10000 Р</div>  
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
                
                   <?php            
                        //$script = <<< JS
                            // Закрытие фона модального окна
                        //     $('.register.active').click(function(e){
                        //         // отправка формы по pjax и потом удаление фона:        
                        //         $('.modal-backdrop.fade.in').css('display','none'); 
                        //         $('body').removeAttr('class');              
                        //     });

                        // JS;
                        //маркер конца строки, обязательно сразу, без пробелов и табуляции
                        //$this->registerJs($script, yii\web\View::POS_READY);
                    ?>
                    <?php //Pjax::end(); 
                    // <!--Выбрать исполнителем -Конец       -->
                }else{ ?>     
            
            <!-- модальное окно - подтвердить выполнение   -->
           
                <a href="#!" class='choose'>Подтвердить выполнение</a>
                <?php } ?>              

            <!-- модальное окно - отказать Исполнителю  -->           
                <!-- <a href="#!" class="cancel">Отказаться</a> -->
                <?php 
                // модальное окно - Отказать  исполнителю                  
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

                <?= $form->field($order_exec, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>
                <?= $form->field($order_exec, 'exec_id')->hiddenInput(['value' => $exec_id])->label(false) ?>

                <div class="choose_buttons">
                    <?= Html::submitButton('Отказать', ['class' => 'register active']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?php 
                Modal::end();                   
                // модальное окно - Отказать  исполнителю -Конец   
                ?>           
            
            <!-- модальное окно - пожаловаться на Исполнителя  -->
                <?php 
                if($ischoose){   ?> 
                    <a href="#!" class="contacts">Пожаловаться на исполнителя</a>
                <?php } ?>      
        </div>
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