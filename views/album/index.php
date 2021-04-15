<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\models\WorkForm;

require_once('../libs/user_photo.php');


$this->title = 'Albums';
$this->params['breadcrumbs'][] = $this->title;

TemplateAsset::register($this);
RegistrationAsset::register($this);

?>

<div class="wrapper__addorder wrapper__addorder__card">
    <div class="b_header b_header__tuning">
        <div class="b_avatar b_avatar__tuning">
            <img src="<?= user_photo($identity['avatar'])?>" alt="">
        </div>

       <!--  <div class="clearfix"></div> -->
        <div class="b_text b_text__tuning">
            <span class="fio"><?= $work_form_name." - ".$identity['username'] ?></span>
        </div>
        <?php   if ( $identity['isconfirm'] ){ //Если профиль проверен-показываем?>     
            <div class="checked">Профиль проверен</div>             
        <?php   } ?>
         
    </div>

    <div class="order_content order_content__tuning">
        <div class="order_content__subtitle">Список альбомов</div>                    
        <div class="text">Отредактируйте альбомы или добавьте новый</div>
        
        <p class="new_album">
            <?= Html::a('Добавить альбом', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
                //'user_id',
                'album_name',
                [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '80'],
                'template' => '{update} {delete}{link}',
                //'template' => '{view} {update} {delete}{link}',
                ],
            ],
        ]); ?>
    </div>

</div>
