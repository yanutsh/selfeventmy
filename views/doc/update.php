<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

//require_once('../libs/user_photo.php');

TemplateAsset::register($this);
RegistrationAsset::register($this);

$this->title = 'Update Doc Photo';
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
//debug($album_photoes);
?>

<!-- <div class="wrapper__addorder wrapper__addorder__card"> -->
<div class="wrapper wrapper__doc">    
    <div class="order_content order_content__tuning">
        
            <div class="order_content__title">Подтверждение данных</div>
            
            <!----------------Для исполнителей-------------------------------->
            <!----------------Добавить Фотографии документов------------------>
           
            <div class="subtitle__doc"><span>Пришлите скриншоты документов для подтверждения вашего статуса и повышения рейтинга.</span>

            <p class='require_list'>Необходимые документы для физ. лиц:</p>
            <ul>
                <li>- Паспорт (сфотографируйте без вспышки развороты 2 и 3 страниц вашего паспорта. На снимке должны быть видны все углы документа и хорошо читаться текст).</li>
                <li>- ИНН</li>
                <li>- Ваш портрет (сфотографируйтесь без солнцезащитных очков и при хорошем освещении, лицо должно быть хорошо видно и занимать не менее 2/3 фотографии)</li>
            </ul>

            </div>    

            <?= $this->render('_form', compact('doc_photoes','identity')) ?>
    	
	</div>    

</div>