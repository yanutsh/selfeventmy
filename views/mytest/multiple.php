<?php
    use yii\widgets\Pjax;
    use yii\bootstrap\Html;
    
    ?>
    <h3>Тестовая страница</h3>
    <div class="col-sm-12 col-md-6">
        <?php Pjax::begin(); ?>
        <?= Html::a("Новая случайная строка", ['mytest/string'], ['class' => 'btn btn-lg btn-primary']) ?>
        <h3><?= $randomString ?></h3>
        <?php Pjax::end(); ?>
    </div>
    
    <div class="col-sm-12 col-md-6">
        <?php Pjax::begin(); ?>
        <?= Html::a("Новый случайный ключ", ['mytest/key'], ['class' => 'btn btn-lg btn-primary']) ?>
        <h3><?= $randomKey ?><h3>
                <?php Pjax::end(); ?>
    </div>