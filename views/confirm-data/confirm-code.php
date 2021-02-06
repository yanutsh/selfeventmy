<?php

use yii\helpers\Url;
use app\assets\TemplateAsset;
//use app\assets\ConfirmCodeAsset;
use app\components\page\PageAttributeWidget as PAW;


TemplateAsset::register($this);
//ConfirmCodeAsset::register($this);


$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
?>

<div class="page-regcust">    

    <div class="wrapper__regcust">

        <div class="regcust regcust__confirm">
            <div class="confirm_title"><?= $what_confirm ?> подтвержден</div>
            <img src="/web/uploads/images/confirm.png" alt="Подтверждено" class="confirm_img">
            <?php echo "Гость=".Yii::$app->user->isGuest; ?>
            <p class='main_text'><?= $what_confirm ?> подтвержден. 'Телефон' вы сможете подтвердить в личном кабинете.</p> 
            <div class="form-group">
                
                <?php echo "  Isexec=".$_SESSION['isexec']."<br>"; ?>

                <a href="<?= Url::to(['/user/index'])?>" class='register__user'>Продолжить</a>
            </div>  
        </div>
    </div>
</div> 
         
