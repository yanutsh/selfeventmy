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
<!-- <div class="page-main template-main page"> -->
<div class="page__addorder">    

        <div class="wrapper__addorder">

            <div class="form_addorder_title">Создание заказа</div>

            <div class="cabinet-addOrder">

                <?php  Pjax::begin(); ?>
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'data-pjax' => true,
                        'enctype' => 'multipart/form-data',
                        ], 
                ]); ?>

                <div class="step_one">
                    <?php //debug($subcategory,0); ?>

                    <div class="flex_block">
                        <?= $form->field($model, 'who_need'); ?>
                        <?= $form->field($model, 'city_id')->dropDownList (ArrayHelper::map($city, 'id', 'name'),['prompt'=>'Выберите город', 'class' =>'form-control js-chosen']) ?>
                    </div>    
                    <div class="flex_block">
                        <?= $form->field($model, 'budget_from') ?>
                        <?= $form->field($model, 'budget_to') ?>
                    </div>

                    <div class="choose_elements">
                        
                        <div class="item_choose">
                            <div class="form-group exactly">
                                <div>
                                    <label class="control-label">Указать точную сумму</label>
                                </div>        
                                <div class="toggle-button-cover"> 
                                      <div class="button-cover">
                                        <div class="button r" id="button-1">
                                          <input type="checkbox" class="checkbox tuning" id="sum_exactly">
                                          <div class="knobs"></div>
                                          <div class="layer"></div>
                                        </div>
                                      </div>
                                </div>
                            </div>

                            <?= $form->field($model, 'order_budget')->label(false) ?>
                        </div>

                        <div class="item_choose">    
                            <div class="form-group exactly">
                                <div>
                                    <label class="control-label">Предоплата</label>
                                </div>        
                                <div class="toggle-button-cover"> 
                                      <div class="button-cover">
                                        <div class="button r" id="button-1">
                                          <input type="checkbox" class="checkbox tuning" id="predoplata">
                                          <div class="knobs"></div>
                                          <div class="layer"></div>
                                        </div>
                                      </div>
                                </div>
                            </div>
                            
                            <?= $form->field($model, 'prepayment')->label(false) ?>
                        </div>
                    </div> 

                   
                    <!-- блок для клонирования категорий и подкатегорий -->
                    <div class="cont" id="c0">                        
                        <?= $form->field($model, 'category_id[]')->dropDownList 
                            (ArrayHelper::map($category, 'id', 'name'),['prompt'=>'Все категории',
                            'id'=>'category_0', 'value'=> $model->category_id[0]]) ?>
                    	
                        <?= $form->field($model, 'subcategory_id[]')->dropDownList 
                            (ArrayHelper::map($subcategory[0], 'id', 'name'),['prompt'=>'Все подкатегории','id'=>'subcategory_0', 'value'=> $model->subcategory_id[0]]); ?>      
                    </div>

                    <div class="cont<?php if (!empty($subcategory[1])) echo " active"?>" id="c1">
                            <?= $form->field($model, 'category_id[]')->dropDownList 
                                (ArrayHelper::map($category, 'id', 'name'),['prompt'=>'Все категории',
                                'id'=>'category_1', 'value'=> $model->category_id[1]]) ?>
                            
                            <?= $form->field($model, 'subcategory_id[]')->dropDownList 
                                (ArrayHelper::map($subcategory[1], 'id', 'name'),['prompt'=>'Все подкатегории','id'=>'subcategory_1', 'value'=> $model->subcategory_id[1]]); ?>
                    </div>    

                    <div class="cont<?php if (!empty($subcategory[2])) echo " active"?>" id="c2">
                            <?= $form->field($model, 'category_id[]')->dropDownList 
                                (ArrayHelper::map($category, 'id', 'name'),['prompt'=>'Все категории',
                                'id'=>'category_2', 'value'=> $model->category_id[2]]) ?>
                            
                            <?= $form->field($model, 'subcategory_id[]')->dropDownList 
                                (ArrayHelper::map($subcategory[2], 'id', 'name'),['prompt'=>'Все подкатегории','id'=>'subcategory_2', 'value'=> $model->subcategory_id[2]]); ?>
                    </div>            

                    <div type="button" class="add_category">Ищу несколько исполнителей</div>                    
                    
                                       
                    
                     <div class="form-group">
                        <button id='continue' class = 'register__user'>Продолжить</button>
                    </div>
                </div>

                <div class="step_two">

                    <?= $form->field($model, 'members') ?> 
                    
                    <?php
                        if (isset($model->date_from)) $date_from = $model->date_from;
                        else $date_from = Yii::$app->params['event_date_from'];
                        if (isset($model->date_to)) $date_to = $model->date_to;
                        else $date_to = Yii::$app->params['event_date_to']; ?>

                        <div class="form-group">
                            <label class="control-label">Даты мероприятия</label>'
                            <?php 
                            echo DatePicker::widget([
                                'name' => 'AddOrderForm[date_from]',
                                'value' => $date_from,    //'01.02.2021',
                                'type' => DatePicker::TYPE_RANGE,
                                'name2' => 'AddOrderForm[date_to]',
                                'value2' => $date_to,   //'27.02.2021',
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy'
                                ]
                            ]);
                            ?>
                        </div>    
                              
                    <?= $form->field($model, 'details')->textArea(['placeholder'=>'Опишите мероприятие подробнее']) ?>

                    <?= $form->field($model, 'wishes')->textArea(['placeholder'=>'Ваши пожелания к проведению мероприятия']) ?>

                    <!-------------------Добавить Фотографии заказа---------------------->

                    <?//= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

                    <?php  
                    $max_photos_order=6; 
                    // передаем признак добавления нового заказа в javascript
                    echo   "<script> 
                                var add_new_order=1; // для предпросмотра
                            </script>";
                    ?>

                    <div class="b-add-item">
                        <!-- <a href="#!" class="add-photo">Прикрепить фото (до <?= $max_photos_order ?> шт.) -->
                        <div  class="add-photo">Прикрепить фото (до <?= $max_photos_order ?> шт.) 
                            <input type="file" name="AddOrderForm[imageFiles][]" id="image" accept="image/*" 
                                  onChange="readmultifiles(this.files,<?= $max_photos_order?>)" multiple/>
                        </div>
                    </div>
                    <div class="image-preview image_large">                              
                    </div>                    

                    <!-- вывод flesh - сообщения об ошибках-->
                    <?php if( Yii::$app->session->hasFlash('errors') ): ?>
                         <div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('errors'); ?>
                         </div>
                    <?php endif;?>

                    <input type="text" id="start_record" name="start_record" hidden value='0'/>
                    <div class="form-group">
                        <?= Html::submitButton('Отправить заказ', ['class' => 'register__user', 'id'=>'order_record']) ?>
                    </div>

                    <?php
$script = <<< JS
    // Предотвращение отправки формы по ENTER
    $("input").keydown(function(event){
      if(event.keyCode == 13){                            
          $( document.activeElement ).next().focus(); 
          event.preventDefault();          
          return false;
       }
    });

    // При обновлении обязательных полей-обновляем форму
        $('#addorderform-who_need').on('change',function(event){
            $('#w0').submit();
        })
        $('#addorderform-city_id').on('change',function(event){
            $('#w0').submit();
        })
        $('#addorderform-budget_to').on('change',function(event){
            $('#w0').submit();
        })    
    // При обновлении обязательных полей-обновляем форму Конец
        
    // Изменение категории услуги
        var rememb_category_id;
        $('select').on('click',function(event){
            //console.dir(event.target);
            var el = $( event.target );
            //console.dir(el);
            //category_id = $('.field-addorderform-category_id select').val();
            category_id = el.val();
            //category_id = el.parent().val();
            //console.log("Кликнули category_id="+category_id);
            if (!(rememb_category_id == category_id) && !(category_id == "")) {
                rememb_category_id=category_id;            
                //console.log("Обновляем category_id="+category_id);
                $('#w0').submit();
            }
        });
    // Изменение категории услуги Конец

    // Команда добавить категорию
        $('.add_category').on('click',function(event){
            //alert("Добавить категорию");
            $('div.cont').each(function (index, element) {
                
                //console.log('Индекс элемента div: ' + index + '; display элемента = ' + $(element).css('display'));
                if ($(element).css('display')=='none') {
                    $(element).css('display','block');
                    $(element).attr('class','active');
                    return false;
                }
            });        
         });

    // Установка признака нажатия на кнопку Submit - Записать заказ
         $('#order_record').on('click', function(event){
            //alert("Записать заказ");
            console.log($('#start_record').val());
            $('#start_record').val('1');
            //console.log($('#start_record').val());
        })

    // Переключатель показа поля точного бюджета)
        $('#sum_exactly').click(function(event) { 
            //alert ("Показать");
            if ($("#sum_exactly").prop("checked")) 
                $('#addorderform-order_budget').show(1000); 
            else { 
                $('#addorderform-order_budget').hide(1000);  
                $('#addorderform-order_budget').val(null);
            }
                       
        }) 

    // Присвоение знач. Бюджета От и До при установке точного значения
        $('#addorderform-order_budget').change(function(event) { 
            if ($('#addorderform-order_budget').val()>0) {
                //alert ("Сброс от и до");
                $('#addorderform-budget_from').val($('#addorderform-order_budget').val());
                $('#addorderform-budget_to').val($('#addorderform-order_budget').val());
            }

        }); 

    // При изменении Бюджета От или До точное значениe cбрасывается
        $('#addorderform-budget_from').change(function(event) { 
            $('#addorderform-order_budget').val(null);
        }); 

        $('#addorderform-budget_to').change(function(event) { 
            $('#addorderform-order_budget').val(null);
        });   

        
    // Переключатель показа поля предоплаты)
        $('#predoplata').click(function(event) { 
            //alert ("Показать");
            if ($("#predoplata").prop("checked")) 
                $('#addorderform-prepayment').show(1000);   
            else { 
                $('#addorderform-prepayment ').hide(1000);
                $('#addorderform-prepayment ').val(null);
            }
                       
        })

    // Действие по кнопке Продолжить
        $('#continue').click(function(event) {
            //alert("Продолжить");
            event.preventDefault();
            $('.step_one').css('display','none');
            $('.step_two').css('display','block');
            $('body,html').animate({scrollTop: 0}, 400);
        }) 

    // для выпадающего списка Город
        $(document).ready(function(){
            $('.js-chosen').chosen({
                width: '100%',
                no_results_text: 'Совпадений не найдено',
                placeholder_text_single: 'Выберите город'
            });
        });

JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>
                        
                    
                    <?php ActiveForm::end(); ?>
                    <?php Pjax::end(); ?>
                </div>    
            </div><!-- page-regCust -->           
           
        </div>
    
</div> 



            
