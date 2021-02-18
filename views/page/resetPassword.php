<?php
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\assets\SendCodeAsset;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);
SendCodeAsset::register($this);

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-regcust">
    <div class="wrapper__regcust">             
        <div class="regcust">
            <div class="confirm_title"><?= Html::encode($this->title) ?></div>
            
            <?php  Pjax::begin();  ?>
            <?php $form = ActiveForm::begin([
                'id' => 'reset-password-form',
                'options' => ['data-pjax' => true,]                   
                ]); ?> 

                <div class="pass__f1">
                        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'id'=>'regform-password']) ?>
                        <a href="#!" class="password-control1"></a>
                    </div>
                    <div class="pass__f2">    
                        <?= $form->field($model, 'password_repeat')->passwordInput(['id'=>'regform-password_repeat']) ?>
                        <a href="#!" class="password-control2"></a>
                    </div>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'register__user']) ?>
                </div>

            <?php ActiveForm::end(); ?>

            <!-- вывод flash - сообщения о результате -->
                <?php if( Yii::$app->session->hasFlash('success') ): ?>
                     <div class="alert alert-success alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <?php echo Yii::$app->session->getFlash('success'); ?>
                     </div>
                                              
                <?php endif;?>
                <!-- вывод flash - сообщения об отправке success-->
                <?php if( Yii::$app->session->hasFlash('error') ): ?>
                     <div class="alert alert-error alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <?php echo Yii::$app->session->getFlash('error'); ?>
                     </div>
                                              
                <?php endif;?> 
            <!-- вывод flash - сообщения о результате -->       
            <?php  Pjax::end();  ?>

        </div>    
    </div>    
</div>
