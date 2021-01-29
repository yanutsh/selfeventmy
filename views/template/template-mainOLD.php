<?php

use yii\helpers\Html;
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
                           
            <div class="header">НАЙДИТЕ ИСПОЛНИТЕЛЯ ДЛЯ ВАШЕГО МЕРОПРИЯТИЯ<br>ИЛИ СТАНЬ ИСПОЛНИТЕЛЕМ И ЗАРАБАТЫВАЙ</div>

            <div class="button-line">
                <div class="element">
                    <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 2]) ?>" class="pink-button">Ищу исполнителя</a><br>
                    <span class="comment">Организуй свой праздник<br>по своим правилам</span>
                </div>
                <div class="element">
                    <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 3]) ?>" class="pink-button">Я исполнитель</a><br>
                    <span class="comment">Получай заказы с гарантией оплаты<br>и зарабатывай на постоянном потоке<br>заказов</span>
                </div>
            </div>

            <div class="avert-place"></div>

            <div class="block-header">
                с нами удобно
            </div>

            <ul class="category-list">
                <li><span><a>Фото</a></span></li>
                <li><span><a>Ведущий</a></span></li>
                <li><span><a>Автомобили</a></span></li>
                <li><span><a>Ресторан</a></span></li>
                <li><span><a>Музыка</a></span></li>
                <li><span><a>Кондитерская</a></span></li>
            </ul>

            <div class="all-categories">
                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 2]) ?>" class="pink-button center">Все категории</a>
            </div>

            <div class="block-header">
                топ 100 исполнителей
            </div>

            <div class="slider-top100">
                <?
                $items       = [];
                for($i = 0; $i < 50; $i++)
                {
                    $items[] = ['content' => '<a href="' . yii\helpers\Url::to(['page/frontend', 'id' => 4]) . '"><span class="image"><img src="' . \Yii::$app->params['image_dir_url'] . 'facelance.png"></span><span class="checked">Проверено</span></a>'];
                }
                ?>
                <?=
                app\components\fork\flexslider\FlexSlider::widget([
                    'items'         => $items,
                    'pluginOptions' => [
                        'controlNav' => false,
                        'itemWidth'  => 130,
                        'animation'  => 'slide',
                        'slideshow'  => false,
                        'move'       => 1,
                    ],
                    'options'       => ['class' => 'carousel']
                ])
                ?>
            </div>

            <div class="all-artists">
                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 2]) ?>" class="pink-button">Все исполнители</a>
            </div>

        </div>

    </section>

    <section class="section-2">
        <div class="wrapper">

            <div class="header">Последние заявки</div>

            <div class="list-request">
                <div class="element">
                    <div class="image"><img src="<?= \Yii::$app->params['image_dir_url'] ?>req-img.png"></div>
                    <div class="b">
                        <div class="name">Иван Иванов</div>
                        <div class="time">2 минуты назад</div>
                        <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                    </div>
                </div>
            </div>

            <div class="all-requests">
                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 3]) ?>" class="pink-button">Все заявки</a>
            </div>

        </div>
    </section>

    <section class="section-3">
        <div class="wrapper">
            <div class="header">Наши новости</div>
        </div>
    </section>

    <?
    if($page['h1'] || $page['content'])
    {
        ?>
        <div class="page-text">
            <div class="wrapper">                        
                <?
                if($page['h1'])
                {
                    ?><h1><?= $page['h1'] ?></h1><? } ?>
                <?= $page['content'] ?>
            </div>
        </div>
    <? } ?>

</div>