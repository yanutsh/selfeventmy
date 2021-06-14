<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderExec */
/* @var $form ActiveForm */
?>
<div class="OrderExec">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'order_id') ?>
        <?= $form->field($model, 'exec_id') ?>
        <?= $form->field($model, 'price') ?>
        <?= $form->field($model, 'prepayment_summ') ?>
        <?= $form->field($model, 'safe_deal') ?>
        <?= $form->field($model, 'result') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- OrderExec -->
