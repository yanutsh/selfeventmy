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
        <div class="balance_content">
            <div class="order_content__title">180 ₽</div>        
            
            <div class="order_content__subtitle">Пополнить баланс </div>
            
            <img src="/web/uploads/images/credit_card_24px.png" alt="" class="karta">
            <div class="text text__card">
                <span>Банковской картой</span>
                <a href="#!">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <p></p>
            
            <img src="/web/uploads/images/credit_card_24px.png" alt="" class="karta">
            <div class="text text__card">
                <span>Банковской картой</span>
                <a href="#!">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <div class="order_content__subtitle">За что происходят списания?</div>
            <div class="text">Lorem, ipsum dolor sit amet consectetur, adipisicing elit. Laborum perspiciatis excepturi reiciendis consequuntur quasi eveniet consectetur corrupti, ab ipsum totam iste libero quos sit expedita minus temporibus corporis exercitationem sint saepe aut quisquam porro facilis mollitia dicta sequi. Natus quae quas, obcaecati, repudiandae vero, laudantium delectus ab accusantium nostrum perspiciatis praesentium dolorem hic eius similique quisquam quod quasi! Ex nulla nisi eos beatae accusamus similique. Earum, quo, dolor? Ducimus amet iste illum, dolorem ullam laborum fugiat sint veritatis alias, recusandae.</div>
            <a href="#!" class="text_details">Подробнее</a>

            <div class="order_content__subtitle">За что происходят списания?</div>
            <div class="text">Lorem, ipsum dolor sit amet consectetur, adipisicing elit. Laborum perspiciatis excepturi reiciendis consequuntur quasi eveniet consectetur corrupti, ab ipsum totam iste libero quos sit expedita minus temporibus corporis exercitationem sint saepe aut quisquam porro facilis mollitia dicta sequi. Natus quae quas, obcaecati, repudiandae vero, laudantium delectus ab accusantium nostrum perspiciatis praesentium dolorem hic eius similique quisquam quod quasi! Ex nulla nisi eos beatae accusamus similique. Earum, quo, dolor? Ducimus amet iste illum, dolorem ullam laborum fugiat sint veritatis alias, recusandae.</div>
            <a href="#!" class="text_details">Подробнее</a>          
            
            
        </div>   

    </div>        
</div>