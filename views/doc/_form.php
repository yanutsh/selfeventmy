<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Album */
/* @var $form yii\widgets\ActiveForm */
//debug($doc_photoes);
?>

<div class="album-form">
	<?php  Pjax::begin([
        'timeout' => false, 
        'enablePushState' => false,
    ]); ?>
    <?php $form = ActiveForm::begin([
                        'action' => '/doc/update',    
                        'id' => 'doc-form',
                        'options' => [
                            'data-pjax' => true,                           
                            ],
                        ]); ?>
        
    <!-- Выводим фотки для редактирования -->
    <!------------------- Удалить фото документов ---------------------------------->
    <?php
    if(!empty($doc_photoes)) { ?>
        <div class="subtitle__doc">Удалите лишние или добавьте новые:</div>
        <div class="album_photo">
    	    <?php
                foreach($doc_photoes as $photo) {?>
        	    	<div class="photo_item">
        	    		<img src="/web/uploads/images/docs/<?= $photo['photo']?>" alt="">
        	    		<a href="?del_photo_id=<?=$photo['id']?>&photo_name=<?= $photo['photo']?>" class="photo_delete">
        	    			<img class="del_icon" src="/web/uploads/images/delete_icon_32px.png" alt="Удалить" title="Удалить фото">
        	    		</a>
        	    	</div>
        	    <?php } ?>                
        </div>
    <?php } ?>

    <!-------------------Добавить Фотографии в документы ------------------------------>

        <?php
        $max_doc_photos=5; 
        // передаем признак добавления новых фоток в javascript
        echo   "<script> 
                    var add_new_order=1; // для предпросмотра
                </script>";
        ?>

        <div class="b-add-item add__docs">
            <div  class="add-photo">Добавить фото (до <?= $max_doc_photos ?> шт.) 
                <input type="file" name="DocPhoto[]" id="image" accept="image/*" 
                      onChange="readmultifiles(this.files,<?= $max_doc_photos?>)" multiple/>
            </div>
        </div>
        <div class="image-preview image_large">                              
        </div>

                             
    <!-------------------Добавить Фотографии в документы Конец  ---------------------->    

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'register__user active__button save save__photo']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php  Pjax::end(); ?>
</div>
