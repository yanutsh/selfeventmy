<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<div class="page-requests template-requests page">

    <section class="section-1">

        <div class="wrapper">

            <div class="left">
                Чтобы связаться с заказчиком, зарегистрируйтесь
            </div>
            <div class="right">
                <a href="/" class="pink-button">Стать исполнителем и выбрать проект</a>
            </div>

        </div>

    </section>

    <section class="section-2">
        <div class="wrapper">

            <div class="category-list">
                <div class="header">
                    Заказы
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
                        <div class="left">
                            <span class="image">
                                <img src="<?= \Yii::$app->params['image_dir_url'] ?>clientimg.png">
                            </span>
                            <span class="msglink">
                                <a href="#" class="pink-button">Сообщение</a>
                            </span>
                        </div>
                        <div class="right">
                            <div class="name">Иван Иванов</div>
                            <div class="time">2 минуты назад</div>
                            <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                            <span class="tags"><a href="#">Организаторы</a></span>
                            <span class="price">5 000 руб.</span>
                        </div>
                    </div>
                    <div class="element">
                        <div class="left">
                            <span class="image">
                                <img src="<?= \Yii::$app->params['image_dir_url'] ?>clientimg.png">
                            </span>
                            <span class="msglink">
                                <a href="#" class="pink-button">Сообщение</a>
                            </span>
                        </div>
                        <div class="right">
                            <div class="name">Иван Иванов</div>
                            <div class="time">2 минуты назад</div>
                            <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                            <span class="tags"><a href="#">Организаторы</a></span>
                            <span class="price">5 000 руб.</span>
                        </div>
                    </div>
                    <div class="element">
                        <div class="left">
                            <span class="image">
                                <img src="<?= \Yii::$app->params['image_dir_url'] ?>clientimg.png">
                            </span>
                            <span class="msglink">
                                <a href="#" class="pink-button">Сообщение</a>
                            </span>
                        </div>
                        <div class="right">
                            <div class="name">Иван Иванов</div>
                            <div class="time">2 минуты назад</div>
                            <div class="text">Стишки на корпоратив. Срочно нужна помощь!!! Всем привет. Вообщем смысл вопроса в том что нужна помощь в придумывании частушек песенок или стишков на корпоратив.<br>СРОЧНО!!!</div>
                            <span class="tags"><a href="#">Организаторы</a></span>
                            <span class="price">5 000 руб.</span>
                        </div>
                    </div>
                </div>
                <div class="all-artists">
                    <a href="#" class="pink-button">Все заявки</a>
                </div>
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