<?php 
// Отзыв Юзеру и  оценка
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

$user = Yii::$app->user->identity;
$user_id = $user->id;
$isexec = $user->isexec;
?>
<div class="wrapper__addorder wrapper__addorder__card">
    
    <div class="order_content">
        <div class="balance_content">
            <div class="order_content__title">Оставьте отзыв и оцените <?php if($isexec) echo"Заказчика"; else echo"Исполнителя"?></div>            
                      
            <img src="/web/uploads/images/review.png" alt="" class="img__center">
            
            <div class="execReview">

                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($review, 'from_user_id')->hiddenInput(['value' => $user_id])->label(false) ?>
                    <?= $form->field($review, 'for_user_id')->hiddenInput(['value' => $for_user_id])->label(false) ?>
                    <?= $form->field($review, 'order_id')->hiddenInput(['value' => $order_id])->label(false) ?>

                    <div class="label_review">Напишите отзыв</div>
                    <?= $form->field($review, 'review')->textArea()->label(false) ?>

                    <div class="label_review">Оцените <?php if($isexec) echo"Заказчика"; else echo"Исполнителя"?></div>
                    <h3>ВНИМАНИЕ! Это можно сделать только здесь, кликнув только 1 раз</h3>
                    
                    <!-- star rating data-id="user_id" **************************-->
                        <script>  
                            localStorage.removeItem('star_rating');
                            localStorage.removeItem('star_rating_get'); 
                        </script>

                        <?php 
                        // только для отображения рейтинга:
                        //require_once($_SERVER['DOCUMENT_ROOT'].'/libs/star_reting_get_html.php') 

                        // для установки рейтинга:
                        $userid=$for_user_id;
                        require_once($_SERVER['DOCUMENT_ROOT'].'/libs/star_reting_html.php') ?>

                    <!-- star rating data-id="page-1" Конец ********************-->
                    <div class="choose_buttons">
                        <?= Html::submitButton('Оставить отзыв', ['class' => 'register active']) ?>
                    </div>
                <?php ActiveForm::end(); ?>

            </div><!-- execReview -->
            
            
            
        </div>   

    </div>        
</div>