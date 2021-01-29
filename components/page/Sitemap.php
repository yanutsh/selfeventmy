<?php

namespace app\components\page;

use app\models\Page;
use app\models\ProductCategory;
use app\models\Product;
use app\models\News;
use yii\base\Widget;
use Yii;
use yii\helpers\Html;

class Sitemap extends Widget
{

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $items = [];
        $pages = Page::find()->where([ 'status' => 1 ])->andWhere([ 'not', [ 'exclude_sitemap' => 1 ] ])->all();
        foreach( $pages as $p )
        {
            $items[] = Html::a($p->name, [ 'page/frontend', 'id' => $p->id ]);
        }
        $items[]    = '&nbsp;';
        $categories = ProductCategory::find()->where([ 'parent' => 0, 'status' => 1, 'noindex' => 0, 'top_level' => 1 ])->orderBy([ 'order' => SORT_ASC ])->all();

        foreach( $categories as $c )
        {
            $tree = $c->getTree();
            $pt   = [];
            if( $tree )
            {
                foreach( $tree as $k => $node )
                {
                    if( $k > 0 )
                    {
                        $products = Product::find()->where([ 'main_category' => $node['id'], 'status' => 1 ])->orderBy([ 'order' => SORT_ASC ])->all();
                        $pp       = [];
                        foreach( $products as $p )
                        {
                            $pp[] = Html::a($p->name, [ 'page/product', 'id' => $p->id ]);
                        }
                        $pt[] = Html::a($node['name'], [ 'page/category', 'id' => $node['id'] ]) . ($pp ? Html::ul($pp, [ 'encode' => false ]) : '');
                    }
                }
            }
            $products = Product::find()->where([ 'main_category' => $c->id, 'status' => 1 ])->orderBy([ 'order' => SORT_ASC ])->all();
            foreach( $products as $p )
            {
                $pt[] = Html::a($p->name, [ 'page/product', 'id' => $p->id ]);
            }
            $items[] = Html::a($c->name, [ 'page/category', 'id' => $c->id ]) . ($pt ? Html::ul($pt, [ 'encode' => false ]) : '');
        }
        $items[] = '&nbsp;';
        $items[] = Html::a('sitemap.xml', '/sitemap.xml');
        return Html::ul($items, [ 'encode' => false ]);
    }

}
