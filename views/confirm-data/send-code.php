<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;
use app\models\Sex;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>
<!-- признак отправки кода для avascript -->
<script> //var ssend_code='no';</script>
<!-- <div class="page-main template-main page"> -->
<div class="page-regcust">    

        <div class="wrapper__regcust">

            <?php  Pjax::begin();  ?>
            

            <div class="regcust">
                <div class="confirm_title">Подтверждение данных</div>
                <div class="choose_send">Выберите способ получения кода для подтверждения - на телефон или почту</div>

                <!-- Форма выбора способа отправки кода подтверждения -->
                <?php $form = ActiveForm::begin
                    ([
                    'options' => [
                        'data-pjax' => true,
                        ],
                    ]); ?> 

                    <select name="phone_email" class="form-control" aria-required="true" aria-invalid="true">
                        <option value="<?= $_GET['phone'] ?>"><?= $_GET['phone'] ?></option>
                        <option value="<?= $_GET['email']?>"><?= $_GET['email'] ?></option>
                    </select>
                    
                    <div class="form-group">
                        <?= Html::submitButton('Выслать код', ['class' => 'register__user', 'id' => 'send_code']) ?>
                    </div>
                <?php ActiveForm::end(); ?>                

                <!-- Форма отправки кода подтверждения -->
                <?php $form = ActiveForm::begin
                    ([
                    'options' => [
                        'data-pjax' => true,
                        ],
                    ]); ?> 

                    <div class="pass__f1">
                        <div class="form-group field-regcustform-password required">
                            <label class="control-label choose_code" for="">Введите код</label>
                            <input type="password" name="code" id="code" class="form-control" aria-required="true" aria-invalid="true" disabled='disabled'/>
                        </div>    
                        <a href="#!" class="password-control1"></a>
                    </div> 

                    <!-- вывод flash - сообщения об отправке кода-->
                    <?php if( Yii::$app->session->hasFlash('send_code') ): ?>
                         <div class="alert alert-success alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('send_code'); ?>
                         </div>
                         <!-- признак отправки кода для avascript -->
                         <script> 
                            ssend_code='ok';
                            console.log ("ssend_code="+ssend_code);
                        </script>
                    <?php endif;?>                

                    <!-- вывод flesh - сообщения об ошибках-->
                    <?php if( Yii::$app->session->hasFlash('error_code') ): ?>
                         <div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('error_code'); ?>
                         </div>
                         <script> 
                             ssend_code='confirm_error';                      
                             console.log ("ssend_code="+ssend_code);
                        </script>
                    <?php endif;?>

                    <div class="form-group">
                        <?= Html::submitButton('Подтвердить', ['class' => 'register__user', 'disabled'=>'disabled', 'id' => 'confirm_code']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
               
            </div><!-- page-regCust -->
            <?php Pjax::end(); ?>    
        </div>
    
</div> 
         
