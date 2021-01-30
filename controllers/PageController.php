<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use app\models\Article;
use app\models\NavigationMain;
use app\models\Page;
//use app\models\Product;
//use app\models\ProductAttributeToProduct;
//use app\models\ProductCategory;
//use app\models\ProductImage;
use app\models\Settings;
//use app\helpers\CatalogImport;
use app\models\BuyRequestForm;
use app\models\SearchForm;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\User;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'rules' => [
            //         [
            //             'actions'       => ['create', 'update', 'view', 'delete'],
            //             'allow'         => true,
            //             'roles'         => ['@'],
            //             'matchCallback' => function ($rule, $action)
            //             {
            //                 return Yii::$app->user->identity->login == 'admin';
            //             }
            //         ],
            //         [
            //             'actions' => ['personal'],
            //             'allow'   => true,
            //             'roles'   => ['@'],
            //         ],
            //         [
            //             'actions' => ['index', 'frontend', 'search', 'sitemap', 'password-reset', 'artists', 'signup', 'requests'],
            //             'allow'   => true,
            //             'roles'   => [],
            //         ],
            //     ],
            // ],
           
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
   		debug('Page/Index');
    	//return $this->redirect(['page/frontend', 'id' => 1]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = 'admin';

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = 'admin';

        $model = new Page();

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            $parentPages   = Page::find()->where(['status' => 1])->asArray()->all();
		      //  $parentPages   = array_merge([ 'id' => 0, 'name' => '(нет)' ], $parents);
            array_unshift($parentPages, ['id' => 0, 'name' => '(нет)']);
            $model->parent = 0;
            return $this->render('create', [
                        'model'       => $model,
                        'parentPages' => $parentPages,
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = 'admin';

        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
         // print_r(Yii::$app->request->post());

            $post = Yii::$app->request->post();

            foreach($post as $k => $v)
            {
                if(strpos($k, 'attr') !== false)
                {
                    $ark  = explode('-', $k);
                    $aid  = end($ark);
                    if($attr = \app\models\PageAttribute::findOne($aid))
                    {
                        $attr->value = $v;
                        $attr->save();
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
        $parentPages = Page::find()->where(['status' => 1])->asArray()->all();
        $parentPages = array_merge(['id' => 0, 'name' => '(нет)'], $parentPages);

        $siteAttribute = \app\models\SiteAttribute::find()->all();
        $pageAttribute = \app\models\PageAttribute::find()->with('siteAttribute', 'siteAttributeValue')->where(['pageid' => $id])->all();
        $newAttribute  = false;

        if(isset(Yii::$app->request->get()['addprop']))
        {
            $newAttribute = new \app\models\PageAttribute();
        }

        return $this->render('update', [
                    'model'         => $model,
                    'parentPages'   => $parentPages,
                    'siteAttribute' => $siteAttribute,
                    'pageAttribute' => $pageAttribute,
                    'newAttribute'  => $newAttribute,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if(($model = Page::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFrontend($id = Null)
    {    
    	
    	// если id не передали - выводим первую страницу
    	if ($id === Null) $id=1;

        $pageModel = Page::findOne(['id' => $id, 'status' => 1]);
 
 		  //if(\Yii::$app->request->get('region')) {echo "есть запрос региона"; die;}
 		  // если в GET новый город - записываем в кукис
        if(\Yii::$app->request->get('inputCity')) {
            	
            \Yii::$app->view->params['inputCity'] = \Yii::$app->request->get('inputCity'); //
            // записываем город в кукис
            $cookies = \Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name'   => 'city',
                'value'  => \Yii::$app->request->get('inputCity'),
                'expire' => time() + 60 * 60 * 24 * 30,
            ]));
        }
        
 	    if($pageModel)
        {

            $templateName = $pageModel->template;

                       
            if(!$templateName) $templateName = 'template-simple';
            
            //$template     = '../template/' . $templateName;
            $template     = '../page/' . $templateName;

            //$model = new User::
            return $this->render($template, [
                        // common
                        'page' => $pageModel,                        
            ]);
        }
        else
        {
            Yii::$app->response->setStatusCode(404);
            return $this->render('../site/error', [
                        'name'    => 'Страница не найдена',
                        'message' => 'Страница не найдена',
            ]);
        }
    }

    public function actionSearch()
    {
        $settings                         = Settings::find()->where(['id' => 1])->one();
        $this->view->params['settings']   = $settings;
        $navigation                       = NavigationMain::find()->where('id>0')->orderBy(['order' => SORT_ASC])->all();
        $this->view->params['navigation'] = $navigation;

        $this->view->params['breadcrumbs']   = [];
        $this->view->params['breadcrumbs'][] = 'Поиск';

        $searchForm = new SearchForm();
        if($searchForm->load(\Yii::$app->request->get()))
        {
            return $this->render(
                            '../template/template-search', [
                        'products'       => $searchForm->getSearchResult(),
                        'productLimit'   => intval(\Yii::$app->request->get('limit', 21)),
                        'searchString'   => $searchForm->search,
                        'productOrderBy' => \Yii::$app->request->get('orderby', 'price_sort'),
                        'productOrder'   => intval(\Yii::$app->request->get('order', SORT_ASC)),
                            ]
            );
        }
    }

    // public function actionAdmin()
    // {
    //     $login = new LoginForm();


    //     if($login->load(Yii::$app->request->post()))
    //     {
    //    //  $identity = User::findIdentity($login->id);

    //         $login->login();
    //     }

    //     if(Yii::$app->user->isGuest || !Yii::$app->user->identity->login || Yii::$app->user->identity->login != 'admin')
    //     {
    //         $this->layout = 'login';

    //         return $this->render('../site/login', [
    //                     'model' => $login
    //         ]);
    //     }
    //     else
    //     {
    //         $this->layout = 'admin';

    //         return $this->render('../site/admin', [
    //                     'model' => $login,
    //         ]);
    //     }
    // }

    public function actionPasswordReset($token)
    {
       // $this->layout = 'none';

        try
        {
            $model = new \app\models\ResetPasswordForm($token);
        } catch(InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
        {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');

            return $this->redirect(['page/frontend', 'id' => Yii::$app->params['loginPageId']]);
        }

        return $this->render('password-reset', [
                    'model' => $model,
        ]);
    }

    /* public function actionSitemap()
      {
      $settings                         = Settings::find()->where(['id' => 1])->one();
      $this->view->params['settings']   = $settings;
      $navigation                       = NavigationMain::find()->orderBy(['order' => SORT_ASC])->all();
      $this->view->params['navigation'] = $navigation;

      $page = Page::findOne(18);

      if ($page)
      {
      $templateName = $page->template;
      if (!$templateName)
      $templateName = 'template-simple';
      $template     = '../template/' . $templateName;

      $articles        = Article::find()->where(['status' => 1])->all();
      $pages           = Page::find()->where(['status' => 1])->all();
      $testCategories1 = TestCategory1::find()->all();
      $testCategories2 = TestCategory2::find()->all();

      return $this->render($template, [
      'settings'        => $settings,
      'page'            => $page,
      'articles'        => $articles,
      'testCategories1' => $testCategories1,
      'testCategories2' => $testCategories2,
      ]);
      }
      else
      {
      Yii::$app->response->setStatusCode(404);
      return $this->render('../site/error', [
      'name'    => 'Страница не найдена',
      'message' => 'Статьяне найдена',
      ]);
      }
      } */


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
      
      // Регистрация нового юзера
      
      if (!Yii::$app->user->isGuest) {  // если юзер авторизован перенаправляем на главную?
        echo "УЖЕ Залогинился - КУДА ИДЕМ?"; 
        die;
        return $this->goHome();
      }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) 
        {
         
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');

             //echo "Регистрация прошла успешно"; 
             
             // Логинирование только что зарегистрированного пользователя
              $model2 = new LoginForm();
              $model2->username = $model->username;
              $model2->password = $model->password;

              //debug($model2);

              if ($model2->login()) {  // если логинирование прошло
                  //echo ("isGuest-".Yii::$app->user->isGuest);  die;
                  //echo ("Юзер-".Yii::$app->user->identity->username);
                  //die;
                  return $this->goHome();
              }              
        }
        // Логин не прошел  
        return $this->render('signup', [
            'model' => $model, ]);
    }

    public function actionArtists()
    {
        //debug("Artists");
        return $this->render('artists');
    }  

     public function actionRequests()
    {
        //debug("Requests");
        return $this->render('requests');
    }  
}
