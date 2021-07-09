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
<?php Pjax::begin(); ?>

    <?php
    //debug($_SESSION['identity']);
    $k=1;
    $user_id = Yii::$app->user->identity->id;

    if(empty($orders_list)) { ?>
        <div class="subtitle">По заданным критериям заказы не найдены</div>    
    <?php 
        goto met_end;
    }

    foreach ($orders_list as $ol) 
    { ?>
        <a href="/cabinet/order-card?id=<?= $ol['id']?>">
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
                     <div class="order_city"><?php 
                        if( ($ol['date_from']==$ol['date_to']) || is_null($ol['date_to'])){
                            echo (convert_datetime_en_ru($ol['date_from'])['dmY']);
                            echo (" (".convert_datetime_en_ru($ol['date_from'])['w'].")");
                        }else{
                            echo ("с ".convert_datetime_en_ru($ol['date_from'])['dmY']);
                            echo (" (".convert_datetime_en_ru($ol['date_from'])['w'].")");
                            echo (" по ".convert_datetime_en_ru($ol['date_to'])['dmY']);
                            echo (" (".convert_datetime_en_ru($ol['date_to'])['w'].")");
                        }?>
                    </div> 
                     <div class="order_added"><?= showDate(strtotime($ol['added_time']))?></div>
                </div>
            </div>
        </a>   

        <?php 
        if (yii::$app->user->identity->isexec && 
            yii::$app->user->identity->isconfirm) 
        {?>
            <!-- <div class="answer"> -->
                <!-- <div class="text">Ваше предложение будет Х в рейтинге заказа</div> -->
               
                <!-- <div> -->
            <?php //Pjax::begin(); ?> 
            <div class="answer">     
                <?
                // проверяем открыт ли чат по Этому заказу с Этим Исполнителем
                    if(!empty($ol['chats'])){ // есть чат 
                        //debug($ol);
                        foreach($ol['chats'] as $ch) {
                            //debug($ch);
                            if ($ch['exec_id']==$user_id && $ch['chat_status']==1 ) { ?>
                                <div class="text">Ваше предложение NN-е в рейтинге заказа</div>
                                <div class="otklic">По этому заказу чат открыт</div>
                                <?php break;
                            }elseif($ch['exec_id']==$user_id && $ch['chat_status']==0 ) { ?>
                                <div class="otklic otklic_right">По этому заказу чат закрыт</div>
                                <?php break;
                            }    
                        }                              
                    }else{   // нет чата ?>
                        <div class="text">Ваше предложение будет Х-м в рейтинге заказа</div>
                        <?php 
                    // модальное окно - Отклик на заказ 
                        $otklik_price = round($ol['budget_to']*get_royalty($ol['budget_to']));               
                        Modal::begin([
                            'header' => '<h2>Отклик на заказ</h2>',
                            'id' => "modal_response_".$k, 
                            'class' => 'modal_response',
                            'toggleButton' => [
                                'label' => 'Откликнуться за '.$otklik_price.' ₽',
                                'tag' => "a",
                                'class' => 'otklic',
                                'id' => 'toggle_response_'.$k,
                             ],                     
                        ]);

                        $form = ActiveForm::begin([
                                'options' => [
                                    'data-pjax' => true,                           
                                   ],
                                ]); ?>

                            <input hidden type="text" name="form_name" value="order-response">
                            <input hidden type="text" name="order_id" value="<?= $ol['id']?>">
                         
                            <div class="b_response">
                                <div class="b_response_item">
                                    <?= $form->field($orderResponseForm, 'exec_price')->textInput(['id' => 'exec_price_'.$k]) ?>
                                    <?= $form->field($orderResponseForm, 'exeс_prepayment')->textInput(['id' => 'exeс_prepayment_'.$k])  ?>
                                </div>
                                <div class="b_response_item">
                                <?= $form->field($orderResponseForm, 'exec_message')->textarea(['style'=>'resize:vertical', 'rows'=>'5', 'value'=> 'Здравствуйте! '.chr(13).'Готов взяться за ваш заказ. Предварительно, хотелось бы обсудить некоторые детали', 'id'=>'exec_message_'.$k]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('Отправить отклик за '.$otklik_price.' ₽', [
                                    'class' => 'register__user active__button response',
                                    'name' => 'order-response-button',
                                    'id'=> 'order-response-button_'.$k]) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    
                        <?php 
                        Modal::end();                   
                    // модальное окно - Конец             
                    }
                ?>
                
                <?php //Pjax::end(); ?>

                <?php
                    $script = <<< JS
                        // Закрытие фона модального окна
                        $(document).on('click', '[id^=order-response-button_]', function () {
                            //alert ("Закрываем");                           
                            $('.modal-backdrop.fade.in').css('display','none'); 
                            $('body').removeAttr('class');  
                        });                    
                    JS;
                    //маркер конца строки, обязательно сразу, без пробелов и табуляции
                    $this->registerJs($script, yii\web\View::POS_READY);
                ?>
            </div>
        <?php 
        } 
        $k++;
    } ?>

    <div class="paginat"> 
        <?php 
        //debug($model); 
        //echo LinkPager::widget([
        //     'pagination' => $pages, 
        //]);    
        ?>    
    </div> 
<?php
met_end:
Pjax::end(); ?>   