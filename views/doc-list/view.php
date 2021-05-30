<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocList */
?>
<div class="doc-list-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'doc_name',
        ],
    ]) ?>

</div>
