<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;
use app\models\Sex;
use kartik\date\DatePicker;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<!-- <div class="page-main template-main page"> -->
<div class="page-regcust">    

        <div class="wrapper__regcust">

            <div class="form_title">Регистрация заказчика</div>

            <div class="regcust">
                <?php //debug(WorkForm::find()->select(['work_form_name', 'id'])->indexBy('id')->column()) 
                //debug( ['M','Ж']) ?>

                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'isexec')->hiddenInput(['value' => '0'])->label(false); ?>

                    <?//= $form->field($model, 'photo') ?>
                    <div class="form_subtitle">Фотография</div>
                    <div class="user-photo">
                        <img src="/web/uploads/images/upload_icon.svg" alt="Загрузите фото">
                        <div class="photo_text">Выберите или перетащите фотографию для профиля</div>
                    </div>


                    <div class="form_subtitle">Основная информация</div>
                    <?= $form->field($model, 'work_form_id')->dropdownList(
                            WorkForm::find()->select(['work_form_name', 'id'])
                            ->indexBy('id')->column(),
                            ['prompt'=>'Выберите вариант']
                        ); ?>

                    <?= $form->field($model, 'username') ?>                    
                    <?//= $form->field($model, 'created_at') ?>
                    <?//= $form->field($model, 'updated_at') ?>
                    <?//= $form->field($model, 'status') ?>
                    
                    <?//= $form->field($model, 'sex_id') ?>
                    <?= $form->field($model, 'sex_id')->dropdownList(
                            Sex::find()->select(['sex', 'id'])
                            ->indexBy('id')->column(),
                            ['prompt'=>'Выберите вариант']
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
                    
                    <?//= $form->field($model, 'auth_key') ?>
                    <?//= $form->field($model, 'password_reset_token') ?>
                    <?//= $form->field($model, 'verification_token') ?>
                    
                     <?//= $form->field($model, 'personal')->checkbox() ?>

                     <div class="personal_check">
                        <label class="checkbox">
                            
                            <input type="checkbox"  class="chkbox"  
                            id="personal" 
                            name="personal" 
                            value="0">
                            <div class="block-image">
                                <img src="/web/uploads/images/interface-1.png" alt="Согласен">
                            </div> 
                            <div class="personal_check__text">Даю согласие на обработку персональных данных</div>                           
                        </label>
                    </div>
                    <div class="personal_check">
                        <label class="checkbox">
                            
                            <input type="checkbox"  class="chkbox"  
                            id="agreement" 
                            name="agreement" 
                            value="0">
                            <div class="block-image">
                                <img src="/web/uploads/images/interface-1.png" alt="Согласен">
                            </div> 
                            <div class="personal_check__text">Принимаю пользовательское соглашение</div>                           
                        </label>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'register__user']) ?>
                    </div>
                <?php ActiveForm::end(); ?>

            </div><!-- page-regCust -->
        </div>
    
</div> 
            
