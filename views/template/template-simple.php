<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);

$this->params['breadcrumbs'] = [['label' => 'Главная', 'url' => '/']];
$this->params['breadcrumbs'][] = $page->name;
?>

<div class="page-simple template-simple page-<?= $page->id ?> page">
    <div class="wrapper">



        <div class="page-text">
            <h1><?= $page['h1'] ?></h1>
            <?
            $matches = [];
            preg_match_all('/{(.*)}/', $page['content'], $matches);
            if (isset($matches[1])) {
                foreach ($matches[1] as $k => $m) {
                    $f = $m . '::widget';
                    $page['content'] = str_replace($matches[0][$k], $f(), $page['content']);
                }
            }
            ?>

            <?= $page['content'] ?>
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
    'header' => '<span class="top">Нужна консультация по товару? Звоните по номеру ' . Yii::$app->settings->get('tel') . '</span><br><span class="bottom">Или просто заполните форму заявки и мы свяжемся с вами!</span>',
])
?>
            </div>
        </div>
    </section>
</div>
