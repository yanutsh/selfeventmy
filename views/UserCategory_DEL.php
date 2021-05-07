<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserCategory */
/* @var $form ActiveForm */
?>
<div class="UserCategory">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'user_id') ?>

        <!-- Категории -->
        <?//= $form->field($model, 'category_id') ?>
        <?= $form->field($user_category, 'category_id')->dropDownList(	
            	ArrayHelper::map($category, 'id', 'name'),
            	 [  'prompt'=>'Все категории',
                    'id'=>'category_id',
                 ])->label('Выберите категорию услуги') ?>
        
        <!-- Подкатегории          -->
        <?//= $form->field($model, 'subcategory_id') ?>
	        <?= $form->field($user_category, 'subcategory_id[]')->dropDownList(ArrayHelper::map($subcategory, 'id', 'name'),[
	            'prompt'=>'Выберите услугу',
	            'id'=>'subcategory_id',
	            'class' => "js-chosen actions",
				'multiple' => "multiple",
	             ])
	            ->label('Добавьте вид услуги'); ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- UserCategory -->
