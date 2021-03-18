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

//TemplateAsset::register($this);
FontAsset::register($this);
AppAsset::register($this);
CabinetAsset::register($this);

$identity = Yii::$app->user->identity;
//debug ($identity['avatar']);
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
        <title><?= Html::encode(Yii::$app->settings->get('name')) ?></title>
        
        <?php $this->head() ?>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <? // if (Yii::$app->user->id) {
        ?>
        <header>
            <div class="line-bottom">
                <div class="wrapper">
                    <div class="wrapper__header">

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

                        <div class="slogan">
                            <?= nl2br(Yii::$app->settings->get('slogan')) ?>
                        </div>

                        <!-- Меню -->
                        <div>
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                      </button>                                  
                                    </div>

                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav">
                                            <li class="active"><a href="/cabinet/index">Заказы <span class="sr-only">(current)</span></a></li>
                                            <li><a href="#">Чат</a></li>
                                            <li><a href="#">Исполнители</a></li>
                                            <li class="dropdown">
                                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $username ?> <span class="caret"></span></a>
                                              <ul class="dropdown-menu">
                                                <li><a href="#">Баланс: 180 руб.</a></li>
                                                <li><a href="#">Помощь</a></li>
                                                <li><a href="/cabinet/user-tuning">Настройки</a></li>
                                                <li><a href="#">Абонементы</a></li>     
                                              </ul>
                                            </li>
                                        </ul>                                
                                      
                                    </div><!-- /.navbar-collapse -->
                                </div><!-- /.container-fluid -->
                            </nav> 
                        </div>

                        <div class="navbar_img">
                            <img src="/web/uploads/images/users/<?= $avatar?>" alt="Аватар">
                        </div>

                    </div>    
                </div>   
            </div>            
        </header>
        <!-- <?php 
            $model=$this->params['model'] ;
            $category=$this->params['category'] ;
            $work_form=$this->params['work_form'];
            $payment_form=$this->params['payment_form'];
            $count=$this->params['count'];
        ?> -->
          
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
