<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

TemplateAsset::register($this);
RegistrationAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\userEducation */
/* @var $form ActiveForm */
?>
<div class="EducationForm">

    <?php $form = ActiveForm::begin(); ?>

        <?//= $form->field($model, 'user_id') ?>
        <?= $form->field($model, 'institute') ?>
        <?= $form->field($model, 'course') ?>
        <?= $form->field($model, 'start_date') ?>
        <?= $form->field($model, 'end_date') ?>
        
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- EducationForm -->
