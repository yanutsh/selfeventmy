<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\web\User;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;
//use app\components\page\PageAttributeWidget as PAW;
// use app\models\WorkForm;
// use app\models\Sex;
// use app\models\Category;
// use app\models\City;
// use kartik\date\DatePicker;
use yii\widgets\Pjax;

//require_once('../libs/user_photo.php');

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

?>
<div class="wrapper__addorder wrapper__addorder__card">
    

    <div class="order_content tabs_header">
        <!-- список вкладок  -->
        <ul class="nav nav-pills">
            <li class="active"><a href="#tab-1" data-toggle="tab">Недавние</a></li>
            <li><a href="#tab-2" data-toggle="tab">В работе</a></li>
            <li><a href="#tab-3" data-toggle="tab">Выполнено</a></li>
            <li><a href="#tab-4" data-toggle="tab">Архив</a></li>
        </ul>
    </div>

    <div class="order_content">

        <?php  Pjax::begin(); ?>
        <h3> Тест <?= $msg ?></h3>
        <a href="/cabinet/chat-list?var=first">Тест Pjax</a> 

        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade in active">
                <!-- цикл по Недавним чатам -->
                <div class="order_info chat">
                    <div class="number_info"><span>#99</span></div>
                    <div class="text_info">
                        <div class="full_number">Заказ №234567</div>
                        <div class="fio">ИП Сергей Иванович Лекорин</div>
                        <div class="last_message">Добрый вечер. Хотел бы узнать детали проекта. Напишите подробнее. Добрый вечер. Хотел бы узнать детали этого проекта проекта. Напишите подробнее. Добрый вечер. Хотел бы узнать детали проекта. Напишите подробнее.</div>
                    </div>                    
                </div>
                <hr> 

                <div class="order_info chat">
                    <div class="number_info"><span>#99</span></div>
                    <div class="text_info">
                        <div class="full_number">Заказ №234567</div>
                        <div class="fio">ИП Сергей Иванович Лекорин</div>
                        <div class="last_message">Добрый вечер. Хотел бы узнать детали проекта. Напишите подробнее. Добрый вечер. Хотел бы узнать детали этого проекта проекта. Напишите подробнее. Добрый вечер. Хотел бы узнать детали проекта. Напишите подробнее.</div>
                    </div>                    
                </div>
                <hr>                
            </div>

            <div id="tab-2" class="tab-pane fade">
                <!-- цикл по открытым Чатам  -->
                <div class="order_info chat">
                    <div class="number_info"><span>#22</span></div>
                    <div class="text_info">
                        
                    </div>
                </div>
                
            </div>

            <div id="tab-3" class="tab-pane fade">
               <!-- цикл по закрытым Чатам  -->
                <div class="order_info chat">
                    <div class="number_info"><span>#33</span></div>
                    <div class="text_info">
                        
                    </div>
                </div>
                
            </div>

            <div id="tab-4" class="tab-pane fade">
               <!-- цикл по архивным Чатам  -->
                <div class="order_info chat">
                    <div class="number_info"><span>#44</span></div>
                    <div class="text_info">
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <?php  Pjax::end(); ?>        
</div>