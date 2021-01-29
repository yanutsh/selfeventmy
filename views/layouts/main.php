<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\FontAsset;
use app\assets\AppAsset;
use porcelanosa\magnificPopup\MagnificPopup;
use app\components\city\CityWidget;

FontAsset::register($this);
AppAsset::register($this);

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
                            <? } ?></div>

                        <div class="slogan">
                            <?= nl2br(Yii::$app->settings->get('slogan')) ?>
                        </div>

                        <div class="bullet">
                            <div class="el"><span class="number">1 254 </span><br><span class="text">Исполнителей онлайн</span></div>
                            <div class="el"><span class="number">100 </span><br><span class="text">Праздников за неделю</span></div>
                        </div>

                        <!-- <div class="phone-block">
                            <div class="address"><img src="<?= Yii::$app->params['image_dir_url'] ?>place.png"><span><?= Yii::$app->settings->get('address') ?></span></div>
                            <div class="phone"><img src="<?= Yii::$app->params['image_dir_url'] ?>phone.png"><span><?= Html::a(Yii::$app->settings->get('tel'), 'tel:+' . preg_replace('/[^0-9]/', '', Yii::$app->settings->get('tel'))) ?></span></div>
                        </div> -->

                        <div class="buttons">
                            <a href="#!" class="enter active">Вход</a>
                            <a href="#!" class="register">Регистрация</a>

                        </div>
                    </div>    
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

        <div class="mfp-hide main-foz" id="foz">
            <?=
            app\components\form\FormRenderWidget::widget([
                'fieldSet'      => [
                    'name',
                    'phone',
                    'politics',
                ],
                'formModel'     => app\models\FeedbackForm::class,
                'submitOptions' => [
                    'content' => 'Узнать стоимость',
                ],
                'labelOptions'  => [
                    'politics' => 'Согласен с политикой конфиденциальности',
                ],
                'header'        => 'Узнайте точную стоимость установки и обслуживания систем мониторинга',
            ])
            ?>
        </div>

        <?=
        MagnificPopup::widget(
                [
                    'target'  => '.popup-inline',
                    'options' => [
                        'type' => 'inline',
                    ]
                ]
        )
        ?>

        <?=
        MagnificPopup::widget(
                [
                    'target'  => '.popup-image',
                    'options' => [
                        'type' => 'image',
                    ]
                ]
        )
        ?>

        <?=
        MagnificPopup::widget(
                [
                    'target'  => '.popup-video',
                    'options' => [
                        'type' => 'iframe',
                    ]
                ]
        )
        ?>


        <!-- Кнопка возврата наверх -->
        <div id="top"><span class="image"><?= Html::img(Yii::$app->params['image_dir_url'] . 'top.png') ?></span>
        </div>


        <?= Yii::$app->settings->get('bodycode') ?> 
        

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
