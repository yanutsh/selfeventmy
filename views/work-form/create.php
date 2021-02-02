<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WorkForm */

$this->title = 'Create Work Form';
$this->params['breadcrumbs'][] = ['label' => 'Work Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
