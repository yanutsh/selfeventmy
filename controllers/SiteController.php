<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
//use bigbrush\tinypng\TinyPng;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Settings;
use app\models\NavigationMain;
use app\models\Page;
use app\models\ProductCategory;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => [ 'logout' ],
                'rules' => [
                    [
                        'actions' => [ 'logout' ],
                        'allow'   => true,
                        'roles'   => [ '@' ],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'post' ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
//            'error'   => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $page = Page::findOne([ 'template' => 'template-main' ]);

        debug($page);
         echo ("Запрос есть"); 
        //debug($page,true);
        if (Yii::$app->request->isGET) {
            echo ("Запрос GET");           
        } else {
            echo "Запрос не GET" ;
        }  
        exit;    

        if( $page )
        {
            $pageController = new PageController($this->id, $this->module);
            return $pageController->actionFrontend($page->url);
        }
        else
        {
            Yii::$app->response->setStatusCode(404);
            return $this->render('error', [
                        'name'    => 'Страница не найдена',
                        'message' => 'Страница не найдена',
            ]);
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        //return $this->redirect([ 'page/admin' ]);

        if( !Yii::$app->user->isGuest )
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if( $model->load(Yii::$app->request->post()) && $model->login() )
        {
            return $this->goBack();
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSearch()
    {
        $model = new app\models\SearchForm();

        if( $model->load(Yii::$app->request->post()) )
        {
            if( $model->validate() )
            {
                // form inputs are valid, do something here
                return;
            }
        }

        return $this->render('form/search', [
                    'model' => $model,
        ]);
    }

    public function actionProductImage()
    {
        $this->layout = 'none';
        $products     = \app\models\Product::find()->where([ 'not like', 'image', '/uploads/%', false ])->all();
        foreach( $products as $p )
        {
            if( $p->image )
            {
                $cat      = ProductCategory::find()->where([ 'id' => $p->main_category ])->one();
                if( $cat )
                    $catPath  = $cat->url;
                else
                    $catPath  = 'other';
                $path     = \Yii::getAlias(\Yii::$app->params['image_dir']) . 'catalog/products/' . $catPath . '/';
                if( !file_exists($path) )
                    mkdir($path, 0755);
                $path_url = \Yii::getAlias(\Yii::$app->params['image_dir_url']) . 'catalog/products/' . $catPath . '/';
                $rim      = Yii::$app->security->generateRandomString(8) . '.' . pathinfo($p->image, PATHINFO_EXTENSION);
                while( file_exists($path . $rim) )
                {
                    $rim = Yii::$app->security->generateRandomString(8) . '.' . pathinfo($p->image, PATHINFO_EXTENSION);
                }
                copy($p->image, $path . $rim);
                $p->image = $path_url . $rim;
                $p->save();
                echo $path_url . $rim . '<br>';
            }
        }
        return 'done';
    }

    public function actionError()
    {
        Yii::$app->response->setStatusCode(404);
        return $this->render('error', [
                    'name'    => 'Страница не найдена',
                    'message' => 'Страница не найдена',
        ]);
    }

}
