<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Complain */
/* @var $form ActiveForm */
?>
<div class="Complain">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'from_user_id') ?>
        <?= $form->field($model, 'for_user_id') ?>
        <?= $form->field($model, 'order_id') ?>
        <?= $form->field($model, 'complain') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- Complain -->
