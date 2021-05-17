<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;
use app\models\Sex;
use app\models\City;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

// запоминаем кто регистрируется - Исполнитель или Заказчик
if (isset($_GET['isexec'])) $_SESSION['isexec'] = $_GET['isexec'];
?>

<!-- <div class="page-main template-main page"> -->
<div class="page-regcust">    

        <div class="wrapper__regcust">

            <?php  Pjax::begin();  ?>

            <div class="form_title">Регистрация <?php if($_GET['isexec']) echo "исполнителя"; else echo "заказчика" ?></div>

            <div class="regcust">
                
                <?php  //Pjax::begin();  ?>
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'data-pjax' => true,
                        ],
                ]); ?>

                <!-- вывод flesh - сообщения -->
                <?php if( Yii::$app->session->hasFlash('success') ): ?>
                     <div class="alert alert-success alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <?php echo Yii::$app->session->getFlash('success'); ?>
                     </div>
                <?php endif;?>

                    <?= $form->field($model, 'isexec')->hiddenInput(['value' => $_GET['isexec']])->label(false); ?>

                    <?//= $form->field($model, 'photo') ?>
                    <div class="form_subtitle">Фотография</div>
                    
                    <!-- Ввод и редактирование АВАТАРА ============= -->
                    <?php 
                    require_once(dirname(dirname(dirname(__FILE__))).'/libs/avatar.php') 
                    ?>
                    <!-- Ввод и редактирование АВАТАРА  Конец ====== -->


                    <div class="form_subtitle">Основная информация</div>
                    <?= $form->field($model, 'work_form_id')->dropdownList(
                            WorkForm::find()->select(['work_form_name', 'id'])
                            ->indexBy('id')->column(),
                            ['prompt'=>'Выберите вариант']
                        ); ?>

                    <?= $form->field($model, 'username') ?> 

                    <div class="input__block field-orderfiltrform-city_id">
                        <a href="#!" id="register_reset_city">Cбросить</a>
                        <label class='control-label'>Город (города)</label>

                        <select name="RegForm[city_id][]" id="regform-city_id" class="js-chosen city" multiple="multiple">
                            <?php foreach($city as $c) {?>
                                <option value=<?= $c['id']?>><?= $c['name']?></option>   
                            <?php } ?>                                  
                        </select>                        

                        <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                    </div>                  
                                        
                    
                    <?= $form->field($model, 'sex_id')->dropdownList(
                            Sex::find()->select(['sex', 'id'])
                            ->indexBy('id')->column() //,
                            //['prompt'=>'Выберите вариант']
                        ); ?>                    

                    
                    <?= $form->field($model, 'birthday') -> widget(DatePicker::classname(), [
                            'name' => 'dp_3',
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            
                            'removeButton' => false,
                            //'readonly' => true,
                            'pluginOptions' => [
                            'orientation' => 'center left',
                            //'format' => 'dd/mm/yyyy',
                            'todayHighlight' => true,
                            //'todayBtn' => true,
                            'autoclose'=>true,
                            ]
                        ]) ?>

                    <div class="form_subtitle">Данные для входа</div>
                    <?= $form->field($model, 'phone') ?>
                    <?= $form->field($model, 'email') ?>
                    <div class="pass__f1">
                        <?= $form->field($model, 'password')->passwordInput() ?>
                        <a href="#!" class="password-control1"></a>
                    </div>
                    <div class="pass__f2">    
                        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                        <a href="#!" class="password-control2"></a>
                    </div>                                        
                   

                    <!-- вывод flesh - сообщения об ошибках-->
                    <?php if( Yii::$app->session->hasFlash('errors') ): ?>
                         <div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('errors'); ?>
                         </div>
                    <?php endif;?>


                    <!----------------Для исполнителей-------------------------------->
                    <!----------------Добавить Фотографии документов------------------>
                    <div class="form_subtitle">Документы</div>
                    <p>Пришлите скриншоты документов для подтверждения вашего статуса и повышения рейтинга.</p>

                    <?php 
                    if ($_GET['isexec']==1) {
                        $max_photos_order=6; 
                        // передаем признак добавления нового заказа в javascript
                        echo   "<script> 
                                    var add_new_order=1;
                                </script>";
                        ?>

                        <div class="b-add-item registration">                            
                            <div  class="add-photo">Выберите фотографии документов 
                                      <input type="file" name="RegForm[imageFiles][]" id="image" accept="image/*" 
                                      onChange="readmultifiles(this.files,<?= $max_photos_order?>)" multiple/>
                            </div>
                        </div>
                        <div class="image-preview image_large">                              
                        </div>                  

                       
                    <?php 
                    } ?>

                    <p class='doc_list'>Необходимые документы дл Юр. лиц:</p>
                    <ul>
                        <li>ИНН организации</li>
                        <li>ИНН организации</li>
                        <li>ИНН организации</li>
                    </ul>      
                    <!-----------Добавить Фотографии документов КОНЕЦ---------------> 

                    <div class="personal_check">
                        <label class="checkbox">
                            
                            <input type="checkbox"  class="chkbox" 
                                <?php if ($model->personal=='yes') echo "checked"?> 
                                name="RegForm[personal]"  
                                value="yes">
                            <div class="block-image">
                                <img src="/web/uploads/images/check_box_24px.svg" alt="Согласен">
                            </div> 
                            <div class="personal_check__text">Даю согласие на обработку персональных данных</div>                           
                        </label>
                    </div>
                    
                    <div class="personal_check">
                        <label class="checkbox">
                            
                            <input type="checkbox"  class="chkbox"  
                                <?php if ($model->agreement=='yes') echo "checked"?> 
                                name="RegForm[agreement]" 
                                value="yes">
                            <div class="block-image">
                                <img src="/web/uploads/images/check_box_24px.svg" alt="Согласен">
                            </div> 
                            <div class="personal_check__text">Принимаю пользовательское соглашение</div>                           
                        </label>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'register__user']) ?>
                    </div> 
                <?php ActiveForm::end(); ?>
               
            </div><!-- page-regCust -->
           
            <?php Pjax::end(); ?>
            
        </div>
    
</div>  

            
