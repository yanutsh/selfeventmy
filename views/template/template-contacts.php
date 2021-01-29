<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

$this->params['breadcrumbs'] = [['label' => 'Главная', 'url' => '/']];
$this->params['breadcrumbs'][] = $page->name;
?>

<div class="page-contacts template-contacts page">

    <div class="wrapper">

        <?=
        yii\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => false,
            'itemTemplate' => "<li>{link} /</li>",
        ])
        ?>

        <div class="top-block">
            <div class="left">
                <div class="h"><?= $page->name ?></div>
                <div class="text">
                    <?=
                    PAW::widget([
                        'attributeId' => 5,
                    ])
                    ?>
                </div>
                <div class="h">Мы в социальных сетях</div>
                <?= app\components\social\ContactsSocialWidget::widget() ?>
                <div class="remark">Присоединяйтесь и будьте в курсе<br>наших новостей и акций.</div>
            </div>
            <div class="right">
                <?=
                PAW::widget([
                    'attributeId' => 6,
                ])
                ?>
            </div>
        </div>

        <div class="bottom-block">
            <?=
            PAW::widget([
                'attributeId' => 7,
            ])
            ?>
        </div>

    </div>

    <div class="wide-line"></div>

    <section class="section-foz">
        <div class="wrapper">
            <div class="foz">
                <?=
                app\components\form\FormRenderWidget::widget([
                    'fieldSet' => [
                        'name',
                        'phone',
                        'message',
                        'politics',
                    ],
                    'formModel' => app\models\FeedbackForm::class,
                    'submitOptions' => [
                        'content' => 'Получить ответ',
                    ],
                    'labelOptions' => [
                        'politics' => 'Согласен с политикой конфиденциальности',
                    ],
                    'placeholder' => [
                        'message' => 'Ваш вопрос:',
                    ],
                    'header' => '<span class="top">Нужна консультация? Звоните по номеру ' . Yii::$app->settings->get('tel') . '</span><br><span class="bottom">Или просто заполните форму заявки и мы свяжемся с вами!</span>',
                ])
                ?>
            </div>
        </div>
    </section>

</div>