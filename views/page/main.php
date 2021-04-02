<?php

//use Yii;
use yii\web\User;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

require_once('../libs/time_ago.php');

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<div class="page-main template-main page">

    <section class="section-1">

        <div class="wrapper">   


            <div class="header">Найдите исполнителя для<br>вашего мероприятия или<br>стань исполнителем и<br>зарабатывай</div> 

            <!-- вывод flesh - сообщения об ошибках-->
            <?php if( Yii::$app->session->hasFlash('errors') ): ?>
                 <div class="alert alert-danger alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <?php echo Yii::$app->session->getFlash('errors'); ?>
                 </div>
            <?php endif;?>           

        <h3><?php 
            //echo ("Юзер- ".Yii::$app->user->identity->username);            
        ?></h3>
        <br>

            <div class="search">
                <div class="search__img"></div>                       
                <input type="text" placeholder="Поиск по исполнителям"/>
                <a href="#!" class="search__link">Найти</a>                
            </div>

            <p class="section-1__subtitle">Если ты исполнитель то <a href="#!"><span>зарегистрируйся</span></a> и получай заказы</p>      
        </div> 
    </section>
    
    <div class="wrapper">
        <!-- Рекламный блок -->    
        <div class="avert-place"></div>

        <div class="wrapper__title">Категории</div>
        <p>На нашем сервисе 30 категорий исполнителей и более 40 подкатегорий по услугам - рестораны, ведущие, кейтеринг, эвент-агентства, музыканты, фото, видео, аудио, прокат и аренда, флористы и многие другие.</p>

        <div class="categories">
            <!-- показываем первые 12 категорий -->
            <?php 
            $i=1;
            foreach($categories as $cat){ ?> 
                <div class="item <?php if ($i>12) echo 'item__add';?>"> 
                    <img src="/web/uploads/images/<?php 
                        if ($cat['icon']) echo $cat['icon'];
                        else echo('010-host.png');?>" alt="Иконка категории">
                    <p><?=$cat['name']?></p>
                </div>
            <?php $i++;
            } ?>
               
        </div>            

        <div class="all-categories">
            <button class="pink-button center" id="all_category">Все категории</button>
        </div>

        <div class="block-header wrapper__title">
            Топ 100 исполнителей
        </div>

        <div class="slider-top100">
            <?
            $items       = [];
            for($i = 0; $i < 50; $i++)
            {
                $items[] = ['content' => '
                    <div class="card">
                    <a href="' . yii\helpers\Url::to(['page/frontend', 'id' => 4]) . '"><span class="image"><img src="' . \Yii::$app->params['image_dir_url'] . 'nadezhda.png"></span>
                        <span class="card__name">Надежда</span>
                        <span class="card__proff">Фотограф</span>    
                    </a>
                    </div>'];
            }
            ?>
            <?=
            app\components\fork\flexslider\FlexSlider::widget([
                'items'         => $items,
                'pluginOptions' => [
                    'controlNav' => false,
                    'itemWidth'  => 135,  //130,
                    'animation'  => 'slide',
                    'slideshow'  => false,
                    'move'       => 1,
                ],
                'options'       => ['class' => 'carousel']
            ])
            ?>
        </div> 

    </div>  <!-- Wrapper -->    
   <!--  </section> -->

    <section class="section-2">
        <div class="wrapper">

            <div class="header">Последние заказы</div>
             <p>На нашем сервисе 30 категорий исполнителей и более 40 подкатегорий по услугам - рестораны, ведущие, кейтеринг, эвент-агентства, музыканты, фото, видео, аудио, прокат и аренда, флористы и многие другие.</p>


            <div class="list-request">
                <?php
                foreach ($last_orders as $ol) 
                { ?>
                    <div class="element">
                        <!-- <div class="image"><img src="<?= \Yii::$app->params['image_dir_url'] ?>req-img.png"></div> -->
                        <div class="b">
                            <div class="name">Заказчик: <?= $ol['workForm']['work_form_name']." ". $ol['user']['username']?></div>
                            <div class="category">Требуется: 
                                <span>
                                    <?php 
                                        $category_names = "";
                                        foreach ($ol['category'] as $cat){
                                            if ( $category_names=="" ) $category_names = $cat['name']; 
                                            else $category_names .= ", ".$cat['name'];                
                                        } 
                                        echo $category_names;
                                        ?>
                                </span>
                            </div>            
                            <div class="text"><?= $ol['details'] ?></div>
                            <div class="money"><?= $ol['budget_to'] ?> ₽</div>
                            <div class="city"><?= $ol['orderCity']['name'] ?></div>
                            <div class="time"><?= showDate(strtotime($ol['added_time']))?></div>
                        </div>
                    </div>
                <?php 
                } ?>    

            </div>          

        </div>
    </section>
</div>
<?php   
$script = <<< JS
    // сворачивание категорий
    $('#all_category').on('click',function(e){
        
        if ($(this).text()=="Все категории") {
            $(this).text("Свернуть");
            $('.item.item__add').show(500); //fadeIn(3000); //css('display','block').fadeIn(1000);
        }else{
            $(this).text("Все категории");
            $('.item.item__add').hide(500); //fadeOut(1000); //.css('display','none')            
        }   
    })    
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>