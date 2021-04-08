<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\UserCategory */
/* @var $form ActiveForm */
TemplateAsset::register($this);
RegistrationAsset::register($this);
?>
<div class="cabinet-addUserCategory">
	 <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
	
	
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'user_id') ?>
        <?= $form->field($model, 'category_id') ?>
        <?= $form->field($model, 'subcategory_id') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
   
</div><!-- cabinet-addUserCategory -->
