<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\FontAsset;
use app\assets\AppAsset;
use yii\bootstrap\Modal; // для модального окна
use yii\bootstrap\ActiveForm;
use porcelanosa\magnificPopup\MagnificPopup;
use app\components\city\CityWidget;
use yii\helpers\Url; 
use app\models\LoginForm;
use yii\widgets\Pjax;

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
                            <? } ?>                            	
                        </div>

                        <div class="slogan">
                            <?= nl2br(Yii::$app->settings->get('slogan')) ?>
                        </div>

                        <div class="bullet">
                            <div class="el"><span class="number">1 254 </span><br><span class="text">Исполнителей онлайн</span></div>
                            <div class="el"><span class="number">100 </span><br><span class="text">Праздников за неделю</span></div>
                        </div>

                        <div class="buttons">
				        	<?php if (Yii::$app->user->isGuest) 
				        	{ 
                                $model = new LoginForm(); 
                            ?>
					            <!-- модальное окно Вход    -->						        
						            <?php Modal::begin([
						                'header' => Null,  // '<h2>Hello world</h2>',
                                        'closeButton'=> False,
						                'toggleButton' => [
						                    'label' => 'Вход',
						                    'tag' => 'a',
						                    'class' => 'enter active'
						              ],
						                'footer' => Null, //'Низ окна',
						                'bodyOptions' => ['class' => 'modal__body modal__body__form'],
    						            ]); ?>

    						            <!-- тело окна  -->
						                
						                <img src="/web/uploads/images/logo_modal.svg" alt="Логотип">
						                <div class="modal_header__login">Вход</div>
                                            <?php  //Pjax::begin();  ?>
                                            <?php $form = ActiveForm::begin(
                                                [   'id' => 'login-form',
                                                    'action' => '/page/login',
                                                    'options' => [
                                                    'data-pjax' => true,],
                                                ]); ?>

                                                <?= $form->field($model, 'email')->textInput(
                                                     ['placeholder'=>'Email']
                                                 ) ?>

                                                <?= $form->field($model, 'password')->passwordInput( ['placeholder'=>'Пароль']) ?>

                                                <?//= $form->field($model, 'rememberMe')->checkbox() ?>
                                                <div class="personal_check">
                                                    <label class="checkbox checkbox__login">
                                                        
                                                        <input type="checkbox"  class="chkbox" 
                                                            <?php if ($model->rememberMe=='1') echo "checked"?> 
                                                            name="LoginForm[rememberMe]"  
                                                            value='1'>
                                                        <div class="block-image">
                                                            <img src="/web/uploads/images/check_box_24px.svg" alt="Запомнить меня">
                                                        </div> 
                                                        <div class="personal_check__text">Запомнить меня</div>                           
                                                    </label>
                                                </div>

                                                <!-- <div> -->
                                                   <a href="/page/request-password-reset-token" class = 'password-reset'>Забыли пароль?
                                                   </a>            
                                                <!-- </div> -->

                                                <div class="form-group">
                                                    <?= //Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) 
                                                    Html::submitButton('Войти', ['class' => 'enter active login', 'name' => 'login-button']) ?>
                                                </div>
                                                <a href="<?php echo Url::to(['page/registration']);?>" class="register register__login">Регистрация
                                                </a> 

                                            <?php ActiveForm::end(); ?>
                                      		<?php  //Pjax::end();  ?>				                                                 

						            <?php Modal::end();?>
						        <!-- модальное окно Вход  Конец    -->    

						        <!-- модальное окно Регистрация-1    -->
						       
						            <?php Modal::begin([
						                'header' => Null, //'<h2>Hello world</h2>',
                                        'closeButton'=> False,
						                'toggleButton' => [
						                    'label' => 'Регистрация',
						                    'tag' => 'a',
						                    'class' => 'register'
						                ],
						                'footer' => Null, //'Низ окна',
						                'bodyOptions' => ['class' => 'modal__body'],
						            ]); ?>

						            <!-- тело окна  -->
						                <img src="/web/uploads/images/logo_modal.svg" alt="Логотип">
						                <div class="modal_header">Кто вы?</div>
						                <div class="modal_text">
						                    Если вы - Исполнитель - тот, кто выполняет заказы и готов предоставить свои услуги, то выберите 
						                    <a href="<?php echo Url::to(['page/registration', 'isexec' => '1']);?>">Я исполнитель</a>, а если вы в поисках исполнителей - то выберите 
						                    <a href="<?php echo Url::to(['page/registration', 'isexec' => '0']);?>">Ищу исполнителя</a> 
						                </div>

						                <div class="who_you">
		                                    <div class="who_you__item">
		                                        <a href="<?php echo Url::to(['page/registration', 'isexec' => '1']);?>"> 
		                                            <div class="modal__b1"></div>
		                                        </a>

		                                        <div class="who_you__title">Я исполнитель</div>                                           
		                                        <div class="who_you__text">Получай заказы с гарантией оплаты и зарабатывай на постоянном потоке заказов</div>
		                                    </div>
		                                    <div class="who_you__item">
		                                        <!-- <a href="/registration"> -->
		                                        <a href="<?php echo Url::to(['page/registration', 'isexec' => '0']);?>">    
		                                            <div class="modal__b2"></div>
		                                        </a>

		                                        <div class="who_you__title">Ищу исполнителя</div>

		                                        <div class="who_you__text">Организуй свой праздник<br>по своим правилам
		                                        </div>
		                                    </div>    
		                                </div>                                            

						            <?php Modal::end();?> 
						        <!-- модальное окно Регистрация Конец    -->            
					        <?php 
					        } else {?>  <!--  не гость -->
                                <a href="/cabinet" class="register active">В кабинет</a>
					            <a href="/logout" class="register">Выход</a>
					        <?php } ?>                            
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
        <div id="top"><span class="image__top"><?= Html::img(Yii::$app->params['image_dir_url'] . 'top.png') ?></span>
        </div>


        <?= Yii::$app->settings->get('bodycode') ?> 
        

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
