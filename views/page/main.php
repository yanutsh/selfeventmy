<?php


use yii\web\User;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<div class="page-main template-main page">

    <section class="section-1">

        <div class="wrapper">           

            <div class="header">Найдите исполнителя для<br>вашего мероприятия или<br>стань исполнителем и<br>зарабатывай</div>           

        <h3><?php echo ("Юзер- ".Yii::$app->user->identity->username); ?></h3>
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
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>
            <div class="item"> 
                <img src="/web/uploads/images/010-host.png" alt="Иконка категории">
                 <p>Фотограф</p>
            </div>       
        </div>            

        <div class="all-categories">
            <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 2]) ?>" class="pink-button center">Все категории</a>
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
                <div class="element">
                    <!-- <div class="image"><img src="<?= \Yii::$app->params['image_dir_url'] ?>req-img.png"></div> -->
                    <div class="b">
                        <div class="name">Заказчик: ИП Иван Иванов</div>
                        <div class="category">Требуется: <span>Ведущий</span></div>            
                        <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                        <div class="money">10 000 Р</div>
                        <div class="city">Mocква</div>
                        <div class="time">2 минуты назад</div>
                    </div>
                </div>
                <div class="element">
                    <!-- <div class="image"><img src="<?= \Yii::$app->params['image_dir_url'] ?>req-img.png"></div> -->
                    <div class="b">
                        <div class="name">Заказчик: ИП Иван Иванов</div>
                        <div class="category">Требуется: <span>Ведущий</span></div>            
                        <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                        <div class="money">1 000 Р</div>
                        <div class="city">Mocква</div>
                        <div class="time">2 минуты назад</div>
                    </div>
                </div>
                <div class="element">
                    <!-- <div class="image"><img src="<?= \Yii::$app->params['image_dir_url'] ?>req-img.png"></div> -->
                    <div class="b">
                        <div class="name">Заказчик: ИП Иван Иванов</div>
                        <div class="category">Требуется: <span>Ведущий</span></div>            
                        <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                        <div class="money">900 Р</div>
                        <div class="city">Mocква</div>
                        <div class="time">2 минуты назад</div>
                    </div>
                </div>
            </div>

           <!--  <div class="all-requests">
                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 3]) ?>" class="pink-button">Все заявки</a>
            </div> -->

        </div>
    </section>

    

</div>