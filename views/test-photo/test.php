
<?php 
//use yii\bootstrap\Modal; // для модального окна
use yii\helpers\Url;
use app\assets\FontAsset;
use app\assets\AppAsset; 

use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);
?>
 <p>
    <br><br><br><br><br><br>
    <h1>Test/Index</h1>
 <p>   

<!-- тест отправки смс сообщения -->
    <?php
        //debug(\Yii::$app->params['login_sms']);
        $login='79771512915';
        $password='CKvihRjRHN';
        $$title='Код подтверждения'; 
        $sadr='MrSelfevent';
        $phone='79218471113';
        $data='Код подтверждения - 123456';

        // запрос баланса
        $var = file_get_contents ('http://gateway.api.sc/get/?user='.$login.'&pwd='.$password.'&balance=1');
        echo $var; ?>

<!-- тест отправки смс сообщения -->
        <?php
        //$var = file_get_contents ('http://gateway.api.sc/get/?user='.$login.'&pwd='.$password.'&name_deliver='.$title.'&sadr='.$sadr.'&dadr='.$phone.'&text='.$data);

        //echo $var;

        ?>
<!-- тест отправки смс сообщения Конец-->




<p>
    <br><br><br><br><br><br>
    <h1>Test/Index</h1>
   
    You may change the content of this page by modifying
    the file
    <?php //echo "<br>"; ?> 
    <code><?= __FILE__; ?></code>.
</p>


     
                           
</div>