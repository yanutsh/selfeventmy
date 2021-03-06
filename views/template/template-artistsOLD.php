<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<div class="page-artists template-artists page">

    <section class="section-1">

        <div class="wrapper">

            <div class="left">
                Чтобы найти исполнителя, зарегистрируйтесь
            </div>
            <div class="right">
                <a href="/" class="pink-button">Зарегистрироваться</a>
            </div>

        </div>

    </section>

    <section class="section-2">
        <div class="wrapper">

            <div class="category-list">
                <div class="header">
                    Исполнители
                </div>

                <ul>
                    <li><a href="/">Ведущие</a></li>
                    <li><a href="/">Музыкальная программа</a></li>
                    <li><a href="/">Шоу-программы</a></li>
                    <li><a href="/">Диджеи</a></li>
                    <li><a href="/">Артисты</a></li>
                    <li><a href="/">Знаменитые артисты</a></li>
                    <li><a href="/">Кейтеринг</a></li>
                    <li><a href="/">Кондитерские</a></li>
                    <li><a href="/">Организаторы торжеств</a></li>
                    <li><a href="/">Оформление и декор</a></li>
                    <li><a href="/">Фотографы/видеографы</a></li>
                    <li><a href="/">Персонал</a></li>
                    <li><a href="/">Площадки</a></li>
                    <li><a href="/">Агентства</a></li>
                </ul>

                <div class="avert-1"></div>

                <div class="avert-2"></div>
            </div>

            <div class="right">
                <div class="search">
                    <div class="input-line">
                        <label><span>Поиск</span><input type="text" class="text" placeholder="Введите текст"><button class="search-button"><?= \rmrevin\yii\fontawesome\FA::icon('search') ?></button></label>
                    </div>
                    <div class="input-line">
                        <label><span>Город</span><select class="select">
                                <option>Москва</option>
                                <option>Санкт-Петербург</option>
                                <option>Абакан</option>
                                <option>Барнаул</option>
                                <option>Великий Новгород</option>
                                <option>Грозный</option>
                                <option>Дзержинск</option>
                            </select></label>
                    </div>
                    <div class="input-line">
                        <label><span>Категория</span><select class="select">
                                <option>Ведущие</option>
                                <option>Музыкальная программа</option>
                                <option>Шоу-программы</option>
                                <option>Диджеи</option>
                                <option>Артисты</option>
                                <option>Знаменитые артисты</option>
                                <option>Кейтеринг</option>
                            </select></label>
                    </div>
                    <div class="input-line">
                        <label><span>Порядок</span><select class="select">
                                <option>По цене</option>
                                <option>По рейтингу</option>
                                <option>По количеству заказов</option>
                            </select></label>
                    </div>
                    <div class="input-line">
                        <label><span></span><input type="submit" class="pink-button" value="Найти"/></label>
                    </div>
                </div>

                <div class="search-results">
                    <div class="element">
                        <span class="yellow"></span>
                        <div class="left">
                            <span class="image">
                                <img src="<?= \Yii::$app->params['image_dir_url'] ?>artistimg.png">
                            </span>
                            <span class="name">
                                Ольга Иванова
                            </span>
                            <span class="msglink">
                                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 4]) ?>" class="pink-button">Сообщение</a>
                            </span>
                        </div>
                        <div class="right">
                            <span class="place">Организатор торжеств</span>
                            <span class="exp">опыт работы 5 лет</span>
                            <span class="text">
                                Привет, меня зовут Ольга! За моими плечами  более 100 свадеб, и более 100 отзывов и вообщея крутая выбирай меня
                            </span>
                            <span class="tags"><a href="#">Организаторы</a></span>
                            <span class="price">От 50 000 руб.</span>
                        </div>
                    </div>
                    <div class="element">
                        <span class="yellow"></span>
                        <div class="left">
                            <span class="image">
                                <img src="<?= \Yii::$app->params['image_dir_url'] ?>artistimg.png">
                            </span>
                            <span class="name">
                                Ольга Иванова
                            </span>
                            <span class="msglink">
                                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 4]) ?>" class="pink-button">Сообщение</a>
                            </span>
                        </div>
                        <div class="right">
                            <span class="place">Организатор торжеств</span>
                            <span class="exp">опыт работы 5 лет</span>
                            <span class="text">
                                Привет, меня зовут Ольга! За моими плечами  более 100 свадеб, и более 100 отзывов и вообщея крутая выбирай меня
                            </span>
                            <span class="tags"><a href="#">Организаторы</a></span>
                            <span class="price">От 50 000 руб.</span>
                        </div>
                    </div>
                    <div class="element">
                        <span class="yellow"></span>
                        <div class="left">
                            <span class="image">
                                <img src="<?= \Yii::$app->params['image_dir_url'] ?>artistimg.png">
                            </span>
                            <span class="name">
                                Ольга Иванова
                            </span>
                            <span class="msglink">
                                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 4]) ?>" class="pink-button">Сообщение</a>
                            </span>
                        </div>
                        <div class="right">
                            <span class="place">Организатор торжеств</span>
                            <span class="exp">опыт работы 5 лет</span>
                            <span class="text">
                                Привет, меня зовут Ольга! За моими плечами  более 100 свадеб, и более 100 отзывов и вообщея крутая выбирай меня
                            </span>
                            <span class="tags"><a href="#">Организаторы</a></span>
                            <span class="price">От 50 000 руб.</span>
                        </div>
                    </div>
                </div>
                <div class="all-artists">
                    <a href="#" class="pink-button">Все исполнители</a>
                </div>
            </div>

        </div>
    </section>

    <section class="section-4">
        <div class="wrapper">
            <div class="header">Новая заявка</div>
            <div class="search">
                <div class="input-line">
                    <label><span>Тема</span><input type="text" class="text" placeholder="Введите текст"></label>
                </div>
                <div class="input-line">
                    <label><span>Мероприятие</span><select class="select">
                            <option>Свадьба</option>
                            <option>Юбилей</option>
                            <option>Детский праздник</option>
                        </select></label>
                </div>
                <div class="input-line">
                    <label><span>Город</span><select class="select">
                            <option>Москва</option>
                            <option>Санкт-Петербург</option>
                            <option>Абакан</option>
                            <option>Барнаул</option>
                            <option>Великий Новгород</option>
                            <option>Грозный</option>
                            <option>Дзержинск</option>
                        </select></label>
                </div>
                <div class="input-line">
                    <label><span>Количество человек</span><input type="text" class="text" placeholder="Введите текст"></label>
                </div>
                <div class="input-line">
                    <label><span>Бюджет</span><input type="text" class="text" placeholder="Введите текст"></label>
                </div>
                <div class="input-line">
                    <label><span>Описание события</span><textarea class="textarea" placeholder="Введите текст"></textarea></label>
                </div>
                <div class="input-line">
                    <label><span>Пожелания</span><textarea class="textarea" placeholder="Введите текст"></textarea></label>
                </div>
                <div class="input-line">
                    <label><span></span><input type="submit" class="pink-button" value="Разместить заказ"/></label>
                </div>
            </div>
        </div>
    </section>

    <section class="section-3">
        <div class="wrapper">
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
                <a href="<?= \yii\helpers\Url::to(['page/frontend', 'id' => 2]) ?>" class="pink-button">Все лучшие исполнители</a>
            </div>
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