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

<!-- <div class="page-main template-main page"> -->
<div class="page-regcust">    

        <div class="wrapper__regcust">

            <?php  Pjax::begin();  ?>
            

            <div class="regcust">
                <div class="confirm_title">Подтверждение данных</div>
                <div class="choose">Выберите способ получения кода для подтверждения - на телефон или почту</div>

                <!-- Форма выбора способа отправки кода подтверждения -->
                <?php $form = ActiveForm::begin
                    ([
                    'options' => [
                        'data-pjax' => true,
                        ],
                    ]); ?> 

                    <select name="ConfirmDataForm[phone_email]" class="form-control" aria-required="true" aria-invalid="true">>
                        <option value="<?= $_GET['phone'] ?>"><?= $_GET['phone'] ?></option>
                        <option value="<?= $_GET['email']?>"><?= $_GET['email'] ?></option>
                    </select>                   

                    <!-- вывод flesh - сообщения об ошибках-->
                    <?php if( Yii::$app->session->hasFlash('errors') ): ?>
                         <div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('errors'); ?>
                         </div>
                    <?php endif;?>

                    <div class="form-group">
                        <?= Html::submitButton('Выслать код', ['class' => 'register__user']) ?>
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
                            <label class="control-label choose" for="">Введите код</label>
                            <input type="password" name="ConfirmDataForm[code]" id="regcustform-password" class="form-control" aria-required="true" aria-invalid="true"/>
                        </div>    
                        <a href="#!" class="password-control1"></a>
                    </div>                 

                    <!-- вывод flesh - сообщения об ошибках-->
                    <?php if( Yii::$app->session->hasFlash('errors') ): ?>
                         <div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('errors'); ?>
                         </div>
                    <?php endif;?>

                    <div class="form-group">
                        <?= Html::submitButton('Подтвердить', ['class' => 'register__user']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <?php //Pjax::end(); ?>

            </div><!-- page-regCust -->
            <?php Pjax::end(); ?>    
        </div>
    
</div> 
            
