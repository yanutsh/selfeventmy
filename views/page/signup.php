<?php

//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
//use app\assets\TemplateAsset;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use app\components\page\PageAttributeWidget as PAW;

//TemplateAsset::register($this);
AppAsset::register($this);

//echo "View/Signup"; die;

$this->title = "Регистрация Заказчика"; //$page->name; 

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?> 
        </div>
    </div>
</div>
