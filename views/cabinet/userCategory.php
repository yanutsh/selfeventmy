<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\UserCategory */
/* @var $form ActiveForm */
?>
<div class="UserCategory" style="width: 50%; margin: 0 auto;">

    <?php Pjax::begin(); ?>
    <?php 
    $form = ActiveForm::begin([
                'id' => 'add-category-form',
                'options' => [
                    'data-pjax' => true,                           
                ],
            ]); ?>

        <?//= $form->field($user_category, 'user_id') ?>

        <!-- Категории -->
        <?//= $form->field($model, 'category_id') ?>
        <?= $form->field($user_category, 'category_id')->dropDownList(	
            	ArrayHelper::map($category, 'id', 'name'),
            	 [  'prompt'=>'Все категории',
                    'id'=>'category_id',
                 ])->label('Выберите категорию услуги') ?>
        
        <!-- Подкатегории          -->
        <?php if (!empty($subcategory)) {?>
        <?= $form->field($user_category, 'subcategory_id[]')->dropDownList(ArrayHelper::map($subcategory, 'id', 'name'),[
	            //'prompt'=>'Выберите услугу',
	            'id'=>'subcategory_id',
	            'class' => "js-chosen actionss",
				'multiple' => "multiple",
	             ])
	            ->label('Добавьте вид услуги'); ?>
        <?php } ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name'=>'save_actions', 'id'=>'save_actions','value'=>'true']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    <?php
                    $script = <<< JS
                       
                        $('#category_id').on('change',function(event){
                            event.preventDefault(); 
                            // признак отправки формы НЕ для сохранения данных
                            $('#save_actions').val('false');              
                            $('#add-category-form').submit();
                        });

                        $('#save_actions').on('click', function(e){
                            // признак отправки формы ДЛЯ сохранения данных
                            $(this).val('true');
                        })

                        $('.js-chosen.actionss').chosen({
                            width: '100%',
                            no_results_text: 'Совпадений не найдено',
                            placeholder_text_single: 'Выберите услугу',
                            placeholder_text_multiple: 'Любая услуга',
                        });
                    JS;
                        //маркер конца строки, обязательно сразу, без пробелов и табуляции
                        $this->registerJs($script, yii\web\View::POS_READY);
            ?>
    <?php Pjax::end(); ?>
</div><!-- UserCategory -->
