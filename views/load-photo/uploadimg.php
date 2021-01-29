<?php
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFiles[]') -> widget(FileInput::className(),[
    					'options' => [	'multiple' => true, 
    									'accept' => 'image/*',
    								 ]
    						     ]) ?>  

<?php ActiveForm::end() ?>


<h3>Вариант 11 - AJAX - не рабочий</h3>

<div class="file-loading">
    <input id="input-44" name="input44[]" type="file" multiple>
</div>
