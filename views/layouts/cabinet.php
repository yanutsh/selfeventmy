<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
//use app\assets\FontAsset;
use app\assets\AppAsset;
use app\assets\FontAsset;
use app\assets\CabinetAsset;
//use app\assets\TemplateAsset;
//use yii\bootstrap\Modal; // для модального окна
use yii\bootstrap\ActiveForm;
//use porcelanosa\magnificPopup\MagnificPopup;
//use app\components\city\CityWidget;
use yii\helpers\Url; 
//use app\models\LoginForm;
use yii\widgets\Pjax;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
//use yii\widgets\Menu;

//require_once('../libs/user_photo.php');

//TemplateAsset::register($this);
FontAsset::register($this);
AppAsset::register($this);
CabinetAsset::register($this);

$identity = Yii::$app->user->identity;
$username = $identity['username'];
$avatar = $identity['avatar'];

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?= Html::csrfMetaTags() ?>      
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <? // if (Yii::$app->user->id) {
        ?>
        <header>
            <div class="line-bottom">
                <div class="wrapper">
                   
                    <?php
                    NavBar::begin([
                        //'brandLabel' => Yii::$app->name,
                        'brandUrl' => Yii::$app->homeUrl,
                        'options' => [
                            'class' => '',  //'navbar-inverse navbar-fixed-top',
                        ],
                    ]);                        
                    ?>

                    <div class="wrapper__header">    
                        <div class="header_item">
                            <div class="logo"><?
                                if(is_file(Yii::getAlias('@webroot' . Yii::$app->settings->get('logo'))))
                                {
                                    ?>
                                    <?
                                    if(Yii::$app->request->url == Yii::$app->homeUrl)
                                    {
                                        ?>
                                        <?= Html::img(Yii::$app->imageresize->getUrl('@webroot' . Yii::$app->settings->get('logo'), 166, 77, 'inset')) ?>
                                        <?
                                    }
                                    else
                                    {
                                        ?>
                                        <?= Html::a(Html::img(Yii::$app->imageresize->getUrl('@webroot' . Yii::$app->settings->get('logo'), 166, 77, 'inset')), Yii::$app->homeUrl) ?>
                                    <? } ?>      
                                <? } ?>                            	
                            </div>
                        </div>

                        <div class="header_item">
                            <div class="slogan">
                                <?= nl2br(Yii::$app->settings->get('slogan')) ?>
                            </div>
                        </div>
                        
                        <div class="header_item">
                            <!-- уведомление для чатов -->
                            <div id="badge_chat" class="badge <?php if(Yii::$app->session['kol_new_chats']==0) echo "hidden"; ?>">             
                                <?= Yii::$app->session['kol_new_chats'] ?>                  
                            </div>
                            <?php
                            $balance=185;
                            if ($identity['isexec']) {
                                echo Nav::widget([                               
                                    'items' => [
                                        ['label' => 'Заказы', 'url' => ['/cabinet/index']],
                                        ['label' => 'Чат', 'url' => ['/cabinet/chat-list']],                         
                                        ['label' => 'Баланс '.$balance." ₽", 'url' => ['/cabinet/balance']],
                                        ['label' => $username, 'items' => [
                                            //['label' => 'Баланс', 'url' =>'/cabinet/balance'],
                                            ['label' => 'Помощь', 'url' =>'/cabinet/help'],
                                            ['label' => 'Настройки', 'url' => '/cabinet/user-tuning'],
                                            ['label' => 'Абонемент', 'url' => '/cabinet/abonement-choose'], 
                                            ['label' => 'Выход', 'url' => '/page/logout'],         
                                        ]],
                                    ],
                                   'options' => ['class' => 'navbar-nav navbar-right'],
                                ]);
                            }else{
                                echo Nav::widget([                               
                                    'items' => [
                                        ['label' => 'Заказы', 'url' => ['/cabinet/index']],
                                        ['label' => 'Чат', 'url' => ['/cabinet/chat-list']],                         
                                        ['label' => 'Исполнители', 'url' => ['/cabinet/executive-list']],
                                        ['label' => $username, 'items' => [
                                            ['label' => 'Баланс', 'url' =>'/cabinet/balance'],
                                            ['label' => 'Помощь', 'url' =>'/cabinet/help'],
                                            ['label' => 'Настройки', 'url' => '/cabinet/user-tuning'], 
                                            ['label' => 'Выход', 'url' => '/page/logout'],                             
                                        ]],
                                    ],
                                   'options' => ['class' => 'navbar-nav navbar-right'],
                                ]);
                            }    
                             ?>                            
                            
                        </div>    <!-- </div>  -->

                        <div class="header_item">
                            <div class="navbar_img">
                                <a href="<?=Url::to('/cabinet/user-profile')?>">
                                    <img src="<?= user_photo($avatar)?>" alt="Аватар">
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <?php NavBar::end();?>    
                </div>   
            </div>            
        </header>
                 
        <?= $content ?>
         
                         
        <footer>
        <section class="section__footer">
            <div class="wrapper">

                <div class="footer__flex">
                    <div class="footer__flex__item logo"><img src="<?= Yii::$app->params['image_dir_url'] ?>wlogo.png">
                        <div class="slogan"><?= nl2br(Yii::$app->settings->get('slogan')) ?></div>
                        
                        <div class="soc">
                            <div class="item">
                                <a href="<?= Yii::$app->settings->get('facebook') ?>" class="instagram" target="_blank" rel="nofollow"></a>
                            </div>
                            <div class="item">
                                <a href="<?= Yii::$app->settings->get('facebook') ?>" class="facebook" target="_blank" rel="nofollow"></a>
                            </div>
                            <div class="item">
                                <a href="<?= Yii::$app->settings->get('vkontakte') ?>" class="vkontakte" target="_blank" rel="nofollow"></a>
                            </div>
                            <div class="item">
                                <a href="<?= Yii::$app->settings->get('youtube') ?>" class="youtube" target="_blank" rel="nofollow"></a>
                            </div>
                            <div class="item">
                                <a href="<?= Yii::$app->settings->get('twitter') ?>" class="twitter" target="_blank" rel="nofollow"></a>
                            </div>
                            <div class="item">
                                <a href="<?= Yii::$app->settings->get('googleplus') ?>" class="whatsapp" target="_blank" rel="nofollow"></a>
                            </div>
                        </div>                        
                    </div>
                    
                    <div class="footer__flex__item menu">
                        <ul>
                            <li>O mrselfevent</li>
                            <li>Рекламодателям</li>
                            <li>Все услуги</li>
                            <li>Все отзывы</li>
                        </ul>  
                    </div>

                    <div class="footer__flex__item menu">
                        <ul>
                            <li>O mrselfevent</li>
                            <li>Рекламодателям</li>
                            <li>Все услуги</li>
                        </ul>  
                    </div> 

                    <div class="footer__flex__item menu">
                        <ul>
                            <li>Служба поддержки</li>
                            <li>8 800 88 45 45</li>
                            <li>Будни: с 6 до 22</li>
                            <li>Выходные: c 8 lj 22</li>
                        </ul>  
                    </div> 
                    <div class="shops">
                        <a href="#!">
                            <img src="/web/uploads/images/google_play.png" alt="Google Play">
                        </a>
                        <a href="#!">
                            <img src="/web/uploads/images/app_store.png" alt="Apple Store">
                        </a>
                    </div>
                </div>
                    
                <div class="right">
                    <?= Yii::$app->settings->get('footer_work') ?>
                </div>
                
            </div>
        </section>    
        </footer>

               
        <!-- Кнопка возврата наверх -->
        <div id="top"><span class="image__top"><?= Html::img(Yii::$app->params['image_dir_url'] . 'top.png') ?></span>
        </div>


        <?= Yii::$app->settings->get('bodycode') ?> 
        

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
