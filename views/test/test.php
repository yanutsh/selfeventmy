
<?php 
use yii\bootstrap\Modal; // для модального окна
use yii\helpers\Url;
use app\assets\FontAsset;
use app\assets\AppAsset; 

use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
?>



 <div class="modal__buttons">
    <!-- <a href="/login" class="enter active">Вход</a> -->

    <!-- модальное окно Вход    -->
    <div class="but1">
        <?php Modal::begin([
            'header' => Null, //'<h2>Hello world</h2>',
            'toggleButton' => [
                'label' => 'Вход2',
                'tag' => 'a',
                'class' => 'enter active'
            ],
            'footer' => Null, //'Низ окна',
            'bodyOptions' => ['class' => 'modal__body'],
        ]); ?>

        <!-- тело окна  -->
            <img src="/web/uploads/images/logo_modal.svg" alt="Логотип">
            <div class="modal_header">Кто вы?</div>
            <div class="modal_text">
                Если вы - Исполнитель - тот, кто выполняет заказы и готов предоставить свои услуги, то выберите 
                <a href="<?php echo Url::to(['page/regcust', 'isexec' => '1']);?>">Я исполнитель</a>, а если вы в поисках исполнителей - то выберите 
                <a href="<?php echo Url::to(['page/regcust', 'isexec' => '0']);?>">Ищу исполнителя</a> 
            </div>                                            

        <?php Modal::end();?>
    </div>    
    <!-- модальное окно Вход  Конец    -->    

    <!-- модальное окно Регистрация-1    -->
    <div class="but2"> 
    	<?php Modal::begin([
            'header' => Null, //'<h2>Hello world</h2>',
            'toggleButton' => [
                'label' => 'Реагистрация',
                'tag' => 'a',
                'class' => 'enter active'
            ],
            'footer' => Null, //'Низ окна',
            'bodyOptions' => ['class' => 'modal__body'],
        ]); ?>

        <!-- тело окна  -->
            <img src="/web/uploads/images/logo_modal.svg" alt="Логотип">
            <div class="modal_header">Кто вы?</div>
            <div class="modal_text">
                Если вы - Исполнитель - тот, кто выполняет заказы и готов предоставить свои услуги, то выберите 
                <a href="<?php echo Url::to(['page/regcust', 'isexec' => '1']);?>">Я исполнитель</a>, а если вы в поисках исполнителей - то выберите 
                <a href="<?php echo Url::to(['page/regcust', 'isexec' => '0']);?>">Ищу исполнителя</a> 
            </div>                                            

        <?php Modal::end();?>            	                              
        
    </div>    
    <!-- модальное окно Регистрация Конец    -->        
                           
</div>