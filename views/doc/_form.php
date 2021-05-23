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
        
    <!-- Выводим фотки документов для удаления -->
    <?php
    if(!empty($doc_photoes)) { ?>
        <div class="subtitle__doc">Удалите лишние или добавьте новые:</div>
        <div class="album_photo">
    	    <?php
                $docs_count = count($doc_photoes); // кол уже введенных документов
                //echo('docs_count='.$docs_count);
                $i = 1;
                foreach($doc_photoes as $photo) {?>
        	    	<div class="photo_item">
                        <!-- <a href="/web/uploads/images/docs/<?= $photo['photo']?>" onclick="return hs.expand(this)" > -->
        	    		     <img id="preview<?= $i ?>" src="/web/uploads/images/docs/<?= $photo['photo']?>" alt="">
                        <!-- </a> -->
        	    		<a href="?del_photo_id=<?=$photo['id']?>&photo_name=<?= $photo['photo']?>" class="photo_delete">
        	    			<img class="del_icon" src="/web/uploads/images/delete_icon_32px.png" alt="Удалить" title="Удалить фото">
        	    		</a>
        	    	</div>

        	    <?php $i++;
                } ?>                
        </div>
    <?php } ?>

    <!-------------------Добавить Фотографии в документы ------------------------------>

        <?php
        $max_doc_photos=5; 
        // Добавляем - если $docs_count < $max_doc_photos
        if ($docs_count < $max_doc_photos) {
            // передаем признак добавления новых фоток в javascript
            echo   "<script> 
                        var add_new_order=1; // для предпросмотра
                    </script>";
            ?>

            <div class="b-add-item add__docs">
                <div  class="add-photo">Добавить фото (до <?= $max_doc_photos ?> шт.) 
                    <input type="file" name="DocPhoto[]" id="image" accept="image/*" 
                          onChange="readmultifiles(this.files,<?= $max_doc_photos ?>)" multiple/>
                </div>
            </div>
            <div class="image-preview image_large">                              
            </div>
         <?php } ?>   
                             
    <!-------------------Добавить Фотографии в документы Конец  ---------------------->    

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'register__user active__button save save__photo', 'name'=>'save_docs', 'value'=> $docs_count, 'data-pjax'=>"0"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php  Pjax::end(); ?>
</div>
