<?php

namespace app\components\page;

use app\models\Page;
use Yii;
use yii\web\UrlRuleInterface;

class PageUrl implements UrlRuleInterface
{

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        $url = '/';
        if($route == 'page/article-section')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\ArticleSection::findOne($params['id']);
                if($model)
                {
                    $blogPage = Page::findOne(\Yii::$app->params['blogPageId']);
                    $url      .= $blogPage->url . '/' . $model->url . '/';
                    unset($params['id']);

                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/article')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\Article::findOne($params['id']);
                if($model)
                {
                    $articleSection = \app\models\ArticleSection::findOne($model->section);
                    $blogPage       = Page::findOne(\Yii::$app->params['blogPageId']);
                    $url            .= $blogPage->url . '/' . $articleSection->url . '/' . $model->url . '/';
                    unset($params['id']);

                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/service')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\Service::findOne($params['id']);
                if($model)
                {
//                    $articleSection = \app\models\ArticleSection::findOne($model->section);
                    $servicePage = Page::findOne(\Yii::$app->params['servicePageId']);
                    $url         .= $servicePage->url . '/' . $model->url . '/';
                    unset($params['id']);

                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/portfolio')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\PortfolioElement::findOne($params['id']);
                if($model)
                {
                    $portfolioPage = Page::findOne(\Yii::$app->params['portfolioPageId']);
                    $url           .= $portfolioPage->url . '/' . $model->url . '/';
                    unset($params['id']);

                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/product-category')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\ProductCategory::findOne(['id' => $params['id'], 'status' => 1]);
                if($model)
                {
                    $catalogPage = Page::findOne(\Yii::$app->params['catalogPageId']);
                    $url         .= $catalogPage->url . '/' . $model['url'] . '/';
                    unset($params['id']);
                    if(isset($params['filterId']))
                    {
                        if($filterValue = \app\models\ProductCategoryFilterValue::findOne($params['filterId']))
                        {
                            $url .= $filterValue->url . '/';
                            unset($params['filterId']);
                        }
                    }

                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/product')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\Product::find()->with(['productCategory'])->where(['id' => $params['id'], 'status' => 1])->one();
                if($model)
                {
                    $catalogPage = Page::findOne(\Yii::$app->params['catalogPageId']);
                    $url         .= $catalogPage->url . '/' . $model->productCategory['url'] . '/' . $model->url . '/';
                    unset($params['id']);
                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/industry')
        {
            $model = null;
            if(isset($params['id']))
            {
                $model = \app\models\Industry::findOne($params['id']);
                if($model)
                {
                    $industryPage = Page::findOne(\Yii::$app->params['industryPageId']);
                    $url          .= $industryPage->url . '/';
                    if($model->parent)
                    {
                        $parent = $model->parent;
                        $iurl   = $model->url;
                        while($parent)
                        {
                            if($m = \app\models\Industry::findOne(['id' => $parent, 'status' => 1]))
                            {
                                $iurl   = $m->url . '/' . $iurl;
                                $parent = $m->parent;
                            }
                            else
                                $parent = 0;
                        }
                        $url .= $iurl . '/';
                    }
                    else
                        $url .= $model->url . '/';
                    unset($params['id']);

                    return $url . ($params ? '?' . http_build_query($params) : '');
                }
            }
        }
        if($route == 'page/frontend')
        {
            $model = Page::findOne($params['id']);
            if($model)
            {
                $parentPage = $model->parent;
                while($parentPage)
                {
                    $parent     = Page::findOne($parentPage);
                    $url        .= $parent ? $parent->url . '/' : '';
                    $parentPage = $parent ? $parent->parent : '';
                }
                $url .= $model->url . '/';
                unset($params['id']);
                return $url . ($params ? '?' . http_build_query($params) : '');
            }
        }
//        if( $route == 'page/news' )
//        {
//            if( isset($params['id']) )
//            {
//                $model = \app\models\News::findOne($params['id']);
//                if( $model )
//                {
//                    $url .= 'news/' . $model->url . '/';
//                    unset($params['id']);
//                    return $url . ($params ? '?' . http_build_query($params) : '');
//                }
//            }
//            else
//                return $url . 'news/' . ($params ? '?' . http_build_query($params) : '');
//        }
        /* if( $route == 'page/recipe' )
          {
          if( isset($params['id']) )
          {
          $model    = \app\models\Recipe::findOne($params['id']);
          $category = \app\models\RecipeCategory::findOne($model->category);
          if( $model )
          {
          $url .= 'recipe/' . $category->url . '/' . $model->url . '/';
          unset($params['id']);
          return $url . ($params ? '?' . http_build_query($params) : '');
          }
          }
          elseif( isset($params['category']) )
          {
          $model = \app\models\RecipeCategory::find()->where([ 'id' => $params['category'], 'status' => 1 ])->one();
          if( $model )
          {
          $url .= 'recipe/' . $model->url . '/';
          unset($params['category']);
          return $url . ($params ? '?' . http_build_query($params) : '');
          }
          }
          else
          {
          $model = Page::findOne(30);
          return $url . $model->url . '/' . ($params ? '?' . http_build_query($params) : '');
          }
          } */
//        if( $route == 'page/article' )
//        {
//            if( isset($params['id']) )
//            {
//                $model = \app\models\Article::findOne($params['id']);
//                if( $model )
//                {
//                    $url .= 'article/' . $model->url . '/';
//                    unset($params['id']);
//                    return $url . ($params ? '?' . http_build_query($params) : '');
//                }
//            }
//            else
//                return $url . 'article/' . ($params ? '?' . http_build_query($params) : '');
//        }
//        if( $route == 'page/search' )
//            return $url . 'search/' . ($params ? '?' . http_build_query($params) : '');
//        if( $route == 'page/specials' )
//        {
//            if( isset($params['id']) )
//            {
//                $model = \app\models\Specials::findOne($params['id']);
//                if( $model )
//                {
//                    $url .= 'specials/' . $model->url . '/';
//                    unset($params['id']);
//                    return $url . ($params ? '?' . http_build_query($params) : '');
//                }
//            }
//        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $_path   = $request->getPathInfo();
        $_params = $request->getQueryParams();

        if($_path)
        {
            $_path = explode('/', trim($_path, '/'));

//            if ($_path[0] == 'admin')
//                return false;
//            if( $_path[0] == 'news' )
//            {
//                if( isset($_path[1]) )
//                {
//                    $news = \app\models\News::find()->where([ 'url' => $_path[1] ])->one();
//                    return [
//                        'page/news',
//                        [
//                            'id' => $news->id,
//                        ],
//                    ];
//                }
//            }
//            if( $_path[0] == 'article' )
//            {
//                if( isset($_path[1]) )
//                {
//                    $article = \app\models\Article::find()->where([ 'url' => $_path[1] ])->one();
//                    return [
//                        'page/article',
//                        [
//                            'id' => $article->id,
//                        ],
//                    ];
//                }
//            }
//
//            if( $_path[0] == 'specials' )
//            {
//                if( isset($_path[1]) )
//                {
//                    $specials = \app\models\Specials::find()->where([ 'url' => $_path[1] ])->one();
//                    return [
//                        'page/specials',
//                        [
//                            'id' => $specials->id,
//                        ],
//                    ];
//                }
//            }



            if(count($_path) == 1)
            {
                $page = Page::find()->where(['url' => $_path[0]])->one();
                if($page)
                    return [
                        'page/frontend',
                        [
                            'id' => $page->id,
                        ],
                    ];

                $controller = Yii::$app->createControllerByID('page');
                $action     = $controller->createAction($_path[0]);
                if($action)
                {
                    return [
                        'page/' . $_path[0],
                        \Yii::$app->request->get(),
                    ];
                }
            }

            if(count($_path) > 1)
            {
                $page       = 0;
                $parentPage = 0;
                foreach($_path as $value)
                {
                    if($page = Page::find()->where(['url' => $value, 'parent' => $parentPage])->one())
                    {
                        $parentPage = $page->id;
                    }
                }
                if($parentPage == \Yii::$app->params['blogPageId'])
                {
                    if($article = \app\models\Article::find()->where(['url' => end($_path)])->one())
                    {
                        return [
                            'page/article',
                            [
                                'id' => $article->id,
                            ],
                        ];
                    }
                    if($articleSection = \app\models\ArticleSection::find()->where(['url' => end($_path)])->one())
                    {
                        return [
                            'page/article-section',
                            [
                                'id' => $articleSection->id,
                            ],
                        ];
                    }
                }
                if($parentPage == \Yii::$app->params['catalogPageId'])
                {
                    if($category = \app\models\ProductCategory::find()->where(['url' => $_path[1], 'status' => 1])->one())
                    {
                        if(($filter      = \app\models\ProductCategoryFilter::find()->where(['categoryid' => $category->id])->all()) && ($filterValue = \app\models\ProductCategoryFilterValue::find()->where(['url' => end($_path), 'filterid' => \yii\helpers\ArrayHelper::getColumn($filter, 'id')])->one()))
                        {
                            return [
                                'page/product-category',
                                [
                                    'id'       => $category->id,
                                    'filterId' => $filterValue->id,
                                ],
                            ];
                        }
                        if($product = \app\models\Product::find()->where(['url' => end($_path), 'status' => 1])->one())
                        {
                            return [
                                'page/product',
                                [
                                    'id' => $product->id,
                                ],
                            ];
                        }
                        return [
                            'page/product-category',
                            [
                                'id' => $category->id,
                            ],
                        ];
                    }
                }
                if($parentPage == \Yii::$app->params['portfolioPageId'])
                {
                    if($portfolioElement = \app\models\PortfolioElement::find()->where(['url' => end($_path)])->one())
                    {
                        return [
                            'page/portfolio',
                            [
                                'id' => $portfolioElement->id,
                            ],
                        ];
                    }
                }
                if($parentPage == \Yii::$app->params['servicePageId'])
                {
                    if($serviceElement = \app\models\Service::find()->where(['url' => end($_path)])->one())
                    {
                        return [
                            'page/service',
                            [
                                'id' => $serviceElement->id,
                            ],
                        ];
                    }
                }
                if($parentPage == \Yii::$app->params['industryPageId'])
                {
                    if($industryElement = \app\models\Industry::find()->where(['url' => array_pop($_path), 'status' => 1])->one())
                    {
                        array_shift($_path);
                        $is     = true;
                        $parent = $industryElement->parent;
                        while($_path)
                        {
                            if($p      = \app\models\Industry::find()->where(['url' => array_pop($_path), 'status' => 1, 'id' => $parent])->one())
                                $parent = $p->parent;
                            else
                            {
                                $is = false;
                            }
                        }
                        if($is)
                            return [
                                'page/industry',
                                [
                                    'id' => $industryElement->id,
                                ],
                            ];
                    }
                }
            }
            if($page)
            {
                return [
                    'page/frontend',
                    [
                        'id' => $page->id,
                    ],
                ];
            }
//
//            $path = [];
//            $pcat = [];
//            $fp = isset($_params['filter']) ? $_params['filter'] : [];
//            $filter_id = 0;
//            foreach ($_path as $key => $value) {
//                if (\app\models\ProductCategory::find()->where(['url' => $value])->one()) {
//                    $category = \app\models\ProductCategory::find()->where(['url' => $value])->one();
//                    $path[] = $category->id;
//                    $category_id = $category->id;
//                }
//                elseif( $filter = \app\models\ProductFilterSeo::find()->where([ 'like', 'url', $value . '%', false ])->all() )
//                {
//
//                    $pos = strpos(trim($request->getPathInfo(), '/'), $value);
//                    $fs  = substr(trim($request->getPathInfo(), '/'), $pos);
//
//                    foreach( $filter as $f )
//                    {
//                        if( ( $f->url === $fs) && ( $f->category == $category_id) )
//                        {
//
//                            if( !$fp )
//                                $fp = $f->filter_attribute;
//
//                            $filter_id = $f->id;
//                            $path[]    = $filter_id;
//                        }
//                    }
//                }
//                elseif( $key < count($_path) - 1 )
//                {
//                    return false;
//                }
//                $product_url = $value;
//            }
//            if (count($path) < count($_path) && $product = \app\models\Product::find()->where(['url' => $product_url])->one()) {
//                array_pop($_path);
//                return [
//                    'page/product',
//                    [
//                        'id' => $product->id,
////                        'path'       => $path,
//                    ],
//                ];
//            } elseif (isset($category) && $category) {
////                $cat     = \app\models\ProductCategory::findOne($category_id);
//                $catpath = $category->getPath();
////                file_put_contents('log.txt', var_export($catpath, true) . "\n" . var_export($_path, true));
//                if (count($catpath) == count($_path)) {
//                    return [
//                        'page/category',
//                        [
//                            'id' => $category_id,
////                        'path'        => $path,
//                        ],
//                    ];
//                }
//                if( $fp )
//                {
////                    file_put_contents('log.txt', $category_id . "\n" . var_export($fp, true) . "\n" . $filter_id);
////                    $ff = explode('?', $this->createUrl($manager, 'page/category', array_merge([ 'id' => $category_id ], $request->getQueryParams())))[0];
////                    file_put_contents('url.txt', $request->getPathInfo() . "\n\n" . $ff);
////                    if( '/' . $request->getPathInfo() !== $ff )
////                    {
////                        (new \yii\web\Controller('Page', ''))->redirect($this->createUrl($manager, 'page/category', [ 'id' => $category_id, 'filter' => $fp ]), 301);
////                    }
//                    return [
//                        'page/category',
//                        [
//                            'id'        => $category_id,
////                            'filter'    => $fp,
////                            'filter_id' => $filter_id,
//                        ]
//                    ];
//                }
//            }
        }
        else
        {
            return [
                'page/frontend',
                [
                    'id' => Yii::$app->params['homePageId'],
                ],
            ];
        }
        return false;
    }

}
