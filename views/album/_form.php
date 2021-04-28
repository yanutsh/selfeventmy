<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Album */
/* @var $form yii\widgets\ActiveForm */
//debug($album_photoes);
?>

<div class="album-form">
	
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'album_name')->textInput(['maxlength' => true]) ?>

    <?php  Pjax::begin(); ?>
    <!-- Выводим фотки для редактирования -->
    <!-- id - номер альбома -->
    <div class="album_photo">
	    <?php 
	    foreach($album_photoes as $photo) {?>
	    	<div class="photo_item">
	    		<img src="/web/uploads/images/portfolio/<?= $photo['photo_name']?>" alt="">
	    		<a href="?id=<?=$id?>&del_photo_id=<?=$photo['id']?>" class="photo_delete">
	    			<img class="del_icon" src="/web/uploads/images/delete_icon_32px.png" alt="Удалить">
	    		</a>
	    	</div>
	    <?php } ?>
    </div>
    <?php  Pjax::end(); ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
