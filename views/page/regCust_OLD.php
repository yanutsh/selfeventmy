<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\User;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;

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

                    <?= $form->field($model, 'photo') ?>
                    <?= $form->field($model, 'work_form_id')->dropdownList(
                            WorkForm::find()->select(['work_form_name', 'id'])
                            ->indexBy('id')->column(),
                            ['prompt'=>'Выберите вариант']
                        ); ?>

                    <?= $form->field($model, 'username') ?>                    
                    <?//= $form->field($model, 'created_at') ?>
                    <?//= $form->field($model, 'updated_at') ?>
                    <?//= $form->field($model, 'status') ?>
                    
                    <?= $form->field($model, 'sex') ?>
                    <select id="regcustform-sex2" class="form-control" name="RegCustForm[sex2]" aria-invalid="false">
                        <option vakue='0'>M</option>
                        <option vakue='1'>Ж</option>
                    </select>

                    <?= $form->field($model, 'birthday') ?>

                    <?= $form->field($model, 'phone') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'password') ?>
                    <?//= $form->field($model, 'password_r') ?>
                    
                    <?//= $form->field($model, 'auth_key') ?>
                    <?//= $form->field($model, 'password_reset_token') ?>
                    <?//= $form->field($model, 'verification_token') ?>
                
                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>

            </div><!-- regcust -->
        </div>
    
</div> 
