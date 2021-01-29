<?php 

$this->title = 'Статистика сайта';
$this->params['breadcrumbs'][] = $this->title;

?>
	<!-- Всего пользователей -->
	<div class='row'>
		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $num_users ?></h3>

              <p>Зарегистрировано пользователей</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?= \yii\helpers\Url::to(['user/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $num_users_auth ?></h3>

              <p>Авторизованных пользователей</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?= \yii\helpers\Url::to(['user/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
	        <div class="small-box bg-yellow">
	            <div class="inner">
	              <h3><?= $num_categories ?></h3>

	              <p>Категорий организаторов</p>
	            </div>
	            <div class="icon">
	              <i class="ion ion-person-add"></i>
	            </div>
	            <a href="<?= \yii\helpers\Url::to(['category/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	        </div>
	    </div>    
    </div>    

