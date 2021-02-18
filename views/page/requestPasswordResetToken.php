<?php
// Форма запроса восстановления пароля
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\TemplateAsset;
use app\assets\SendCodeAsset;

use yii\widgets\Pjax;

TemplateAsset::register($this);
SendCodeAsset::register($this);
?>

<script>var timer=<?= \Yii::$app->params['timer'] ?></script>

<div class="page-regcust">
    <div class="wrapper__regcust">
        <div class="regcust">
            <div class="confirm_title">Восстановление пароля</div>
            <!-- <div class="choose_send">Введите email или номер телефона</div> -->
            
            <?php  Pjax::begin();  ?>
            <?php $form = ActiveForm::begin
                ([
                    'id' => 'request-password-reset-form',
                    'options' => [
                        'data-pjax' => true,
                    ],
                ]); ?>

                <?= $form->field($model, 'email'); ?>

                <div class="form-group group__resend">
                    <button type="submit" id="send_code" class="register__user">Отправить</button>

                    <div class="resend">
                        <div class="count__caption">Повторно выслать через - </div>
                        <div id="countdown-1"></div>
                    </div>                                       
                </div>

                <!-- вывод flash - сообщения об отправке success-->
                <?php if( Yii::$app->session->hasFlash('success') ): ?>
                     <div class="alert alert-success alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <?php echo Yii::$app->session->getFlash('success'); ?>
                     </div>
                                              
                <?php endif;?>  

            <?php ActiveForm::end(); ?>
            <!-- <?php  //Pjax::end();  ?>  -->

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
                    <a href="#!" class="password-control3"></a>
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
            <?php Pjax::end(); ?> 
        </div><!-- page-regCust -->
               
    </div>           
</div>    
 
