<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
use app\components\page\PageAttributeWidget as PAW;
use app\models\WorkForm;
use app\models\Sex;
use app\models\Category;
use app\models\City;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);


?>
<div class="wrapper__addorder wrapper__addorder__card">
    
    <div class="order_content">
        <div class="help_content">
            <div class="order_content__title">Помощь</div>        
            
            <div class="order_content__subtitle">Написать в поддержку</div>
            <div class="text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Illo laudantium architecto, quibusdam possimus non rem, molestias cupiditate tempore fugiat accusantium.</div>
            <a href="mailto:admin@selfevent.ru" class="text_details">Написать</a>

            <div class="order_content__subtitle">Часто задаваемые вопросы</div>
            <div class="text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Illo laudantium architecto, quibusdam possimus non rem, molestias cupiditate tempore fugiat accusantium.</div>
            <a href="" class="text_details">Подробнее</a>

            <div class="order_content__subtitle">О Компании</div>
            <div class="text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Illo laudantium architecto, quibusdam possimus non rem, molestias cupiditate tempore fugiat accusantium.</div>
            <a href="" class="text_details">Подробнее</a>

            <div class="order_content__subtitle">Скачать приложение</div>
            <div class="text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Illo laudantium architecto, quibusdam possimus non rem.</div>
            
            
            <div class="help_buttons">
                <!-- <div> -->
                    <a href="https://play.google.com/" class=""><img src="/web/uploads/images/googleplay.png" alt=""></a>
                <!-- </div> -->
                <!-- <div> -->
                    <a href="https://www.apple.com/" class=""><img src="/web/uploads/images/appstore.png" alt=""></a>
                <!-- </div> -->
            </div>
        </div>   

    </div>        
</div>