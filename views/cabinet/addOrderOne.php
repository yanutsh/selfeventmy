<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;
use app\models\Sex;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

?>
<!-- <div class="page-main template-main page"> -->
<div class="page__addorder">    

        <div class="wrapper__addorder">

            <?php  Pjax::begin();  ?>

            <div class="form_title">Создание заказа</div>

            <div class="cabinet-addOrder">
                
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'data-pjax' => true,
                        ],
                ]); ?>

                <?= $form->field($model, 'user_id') ?>
                <?= $form->field($model, 'details') ?>
                <?= $form->field($model, 'city_id') ?>
                <?= $form->field($model, 'members') ?>
                <?= $form->field($model, 'date_from') ?>
                <?= $form->field($model, 'status_order_id') ?>
                <?= $form->field($model, 'order_budget') ?>
                <?= $form->field($model, 'budget_from') ?>
                <?= $form->field($model, 'budget_to') ?>
                <?= $form->field($model, 'prepayment') ?>
                <?= $form->field($model, 'wishes') ?>
                <?= $form->field($model, 'added_time') ?>
                <?= $form->field($model, 'date_to') ?>
                <?= $form->field($model, 'who_need') ?>
                                   
                    

                    <!-- вывод flesh - сообщения об ошибках-->
                    <?php if( Yii::$app->session->hasFlash('errors') ): ?>
                         <div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <?php echo Yii::$app->session->getFlash('errors'); ?>
                         </div>
                    <?php endif;?>

                    <div class="form-group">
                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'register__user']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <?php //Pjax::end(); ?>

            </div><!-- page-regCust -->
           
            <?php Pjax::end(); ?>
            
        </div>
    
</div>  

            
