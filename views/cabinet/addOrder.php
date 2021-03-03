<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form ActiveForm */
?>
<div class="cabinet-addOrder">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'who_need') ?>

        <?= $form->field($model, 'city_id') ?>
        <?= $form->field($model, 'members') ?> 
        <?= $form->field($model, 'date_from') ?>
        <?= $form->field($model, 'date_to') ?>

        
        <?= $form->field($model, 'details') ?>
        <?= $form->field($model, 'status_order_id') ?>
        <?= $form->field($model, 'order_budget') ?>
        <?= $form->field($model, 'budget_from') ?>
        <?= $form->field($model, 'budget_to') ?>
        <?= $form->field($model, 'prepayment') ?>
        <?= $form->field($model, 'wishes') ?>
        <?= $form->field($model, 'added_time') ?>
        <?//= $form->field($model, 'user_id') ?>
       
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- cabinet-addOrder -->
