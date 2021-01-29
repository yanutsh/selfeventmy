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

<div class="page-portfolio template-portfolio page">

    <div class="wrapper">

        <?=
        yii\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => false,
            'itemTemplate' => "<li>{link} /</li>",
        ])
        ?>


        <h1 class="page-header"><?= $page->h1 ?></h1>

        <div class="portfolio-preambula">
            <?=
            PAW::widget([
                'attributeId' => 3,
            ])
            ?>
        </div>

        <?=
        \app\components\portfolio\PortfolioCaseListWidget::widget()
        ?>

    </div>

</div>