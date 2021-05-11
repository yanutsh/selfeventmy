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
	<?php  Pjax::begin([
        'timeout' => false, 
        'enablePushState' => false,
    ]); ?>
    <?php $form = ActiveForm::begin([
                        'action' => '/album/update?id='.$model->id,    
                        'id' => 'album-form',
                        'options' => [
                            'data-pjax' => true,                           
                            ],
                        ]); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['maxlength' => true])->label(false) ?>
    <?= $form->field($model, 'album_name')->textInput(['maxlength' => true]) ?>

    <?php  //Pjax::begin(); ?>
    <!-- Выводим фотки для редактирования -->
    <!-- id - номер альбома -->
    <div class="album_photo">
	    <?php
        if(!empty($album_photoes)) {
    	    foreach($album_photoes as $photo) {?>
    	    	<div class="photo_item">
    	    		<img src="/web/uploads/images/portfolio/<?= $photo['photo_name']?>" alt="">
    	    		<a href="?id=<?=$id?>&del_photo_id=<?=$photo['id']?>" class="photo_delete">
    	    			<img class="del_icon" src="/web/uploads/images/delete_icon_32px.png" alt="Удалить" title="Удалить фото">
    	    		</a>
    	    	</div>
    	    <?php } ?>
        <?php } ?>    
    </div>

    <!-------------------Добавить Фотографии в альбом ------------------------------>

        <?php
        if(!empty($model->id)) { // если альбом уже создан 

            $max_photos_album=6; 
            // передаем признак добавления нового Альбома в javascript
            echo   "<script> 
                        var add_new_order=1; // для предпросмотра
                    </script>";
            ?>

            <div class="b-add-item">
                <!-- <a href="#!" class="add-photo">Прикрепить фото (до <?= $max_photos_order ?> шт.) -->
                <div  class="add-photo">Добавить фото (до <?= $max_photos_album ?> шт. в альбоме) 
                    <input type="file" name="AlbumPhoto[]" id="image" accept="image/*" 
                          onChange="readmultifiles(this.files,<?= $max_photos_album?>)" multiple/>
                </div>
            </div>
            <div class="image-preview image_large">                              
            </div>

        <?php } ?>                       
    <!-------------------Добавить Фотографии в альбом Конец  ---------------------->    

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'register__user active__button save save__photo']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php  Pjax::end(); ?>
</div>
