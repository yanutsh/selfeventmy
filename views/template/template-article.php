<?php

use yii\helpers\Html;
use app\assets\TemplateAsset;
use app\components\page\PageAttributeWidget as PAW;

TemplateAsset::register($this);

$this->title = $page->seo_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->seo_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $page->seo_description]);
/*
  $this->params['breadcrumbs'] = [['label' => 'Главная', 'url' => '/']];

  if (isset($articleSection) && isset($blogPage)) {
  $this->params['breadcrumbs'][] = ['label' => $blogPage->name, 'url' => ['page/frontend', 'id' => $blogPage->id]];
  }

  $this->params['breadcrumbs'][] = $page->name; */
?>

<div class="page-article template-article page">

    <div class="wrapper">

        <? /* =
          yii\widgets\Breadcrumbs::widget([
          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
          'homeLink' => false,
          'itemTemplate' => "<li>{link} /</li>",
          ]) */
        ?>

        <? if ($page->image_main) { ?>
            <div class="bc-image" data-back="<?= $page->image_main ?>">

                <h1 class="article-header"><?= $page->h1 ?></h1>

            </div>
        <? } else { ?>
            <h1 class="article-header"><?= $page->h1 ?></h1>
        <? } ?>

        <div class="article-content">
            <?= $page['content'] ?>
        </div>

        <div class="related-article">
            <?=
            \app\components\blog\BlogRelatedListWidget::widget([
                'articleId' => $page->id,
            ])
            ?>
        </div>

    </div>

</div>