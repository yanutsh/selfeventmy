<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<div class="page-profile template-profile page">

    <section class="section-1">

        <div class="wrapper">

            <div class="left">
                <div class="photo">
                    <img src="<?= Yii::$app->params['image_dir_url'] ?>clientbigimg.png">
                </div>
                <div class="place">Фотограф</div>
                <div class="reviews">
                    <span>Отзыв: <a href="#">45 отзывов</a></span>
                </div>
                <div class="searcher">
                    <span class="header">Ищет заказы:</span>
                    <span class="text">Съемка свадьбы</span>
                    <span class="text">Дата: 22 августа</span>
                    <span class="text">Стоимость: 160 000 руб.</span>
                </div>
                <div class="start">
                    <a href="#" class="pink-button">Начать работу</a>
                    <div class="remark">Нажмите до начала работ, для 
                        безопасной сделки. Вы будете 
                        защищены и сможете:</div>
                    <a href="#" class="action">Оставить отзыв</a><br>
                    <a href="#" class="action">Пожаловаться</a><br>
                    <a href="#" class="action">Поблагодарить</a>
                </div>
                <div class="searcher">
                    <span class="header">Услуги</span>
                    <span class="text">Съемка свадьбы</span>
                    <span class="text">Свадебный день</span>
                    <span class="text">Съемка love story</span>
                    <span class="text">Студийная фотосъемка</span>
                    <span class="more"><a href="#">Подробнее...</a></span>
                </div>
            </div>
            <div class="right">
                <div class="line-1">
                    <span class="name">Иван Иванов</span>
                    <span class="checked"><img src="<?= Yii::$app->params['image_dir_url'] ?>checked.png"></span>
                </div>
                <div class="line-2">
                    г. Москва
                </div>
                <div class="line-3">
                    <span class="form"><span class="h">Форма работы: </span><span class="v">частное лицо</span></span>
                    <span class="sitetime"><span class="h">На сайте: </span><span class="v">799 дней</span></span>
                    <span class="sitetime"><span class="h">Последний визит: </span><span class="v">5 мин. назад</span></span>
                    <span class="sitetime"><span class="h">Сегодня: </span><span class="v">свободен</span></span>
                </div>
                <div class="line-4">
                    <span class="green">работает без предоплаты за услуги</span>
                </div>
                <div class="line-5">
                    Данные подтверждены документом (паспорт)
                </div>
                <div class="line-6">
                    Работает в городах:<br>Москва, Тюмень, Омск, Минск
                </div>
                <div class="line-7">
                    <a href="#" class="pink-button btn1">Сообщение</a>
                    <a href="#" class="pink-button btn2">Получить консультацию</a>
                </div>
                <div class="line-8">
                    Краткое описание.
                </div>
                <div class="line-9">
                    <div class="header">
                        Портфолио
                    </div>
                </div>
                <div class="line-10">
                    <div class="count">67 фотографий</div>
                    <div class="image-line">
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                    </div>
                    <div class="comment-line">
                        <span>35 комметариев</span>
                        <span>34 комметария</span>
                        <span>5 комметариев</span>
                        <span>35 комметариев</span>
                        <span>35 комметариев</span>
                        <span>35 комметариев</span>
                    </div>
                    <div class="more">
                        <a href="#">Смотреть еще ...</a>
                    </div>
                </div>
                <div class="line-10">
                    <div class="count">57 видео</div>
                    <div class="image-line">
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                        <a href="<?= Yii::$app->params['image_dir_url'] ?>ph1.png" class="popup-image"><img src="<?= Yii::$app->params['image_dir_url'] ?>ph1.png"></a>
                    </div>
                    <div class="comment-line">
                        <span>35 комметариев</span>
                        <span>34 комметария</span>
                        <span>5 комметариев</span>
                        <span>35 комметариев</span>
                        <span>35 комметариев</span>
                        <span>35 комметариев</span>
                    </div>
                    <div class="more">
                        <a href="#">Смотреть еще ...</a>
                    </div>
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