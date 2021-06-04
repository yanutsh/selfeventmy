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
use app\models\NavigationMain;
use app\models\Page;
use app\models\Settings;
use app\models\User;
use app\models\UserCity;
use app\models\UserDoc;
use app\models\Category;
use app\models\Order;
use app\models\City;
use app\models\WorkForm;

use app\models\BuyRequestForm;
use app\models\SearchForm;
use app\models\RegForm;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use yii\base\Response;
use yii\web\UploadedFile;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends AppController
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
    }


    //========================== Первичный контроллер ==========================
    public function actionFrontend($id = Null)    {    
      
      // если id не передали - выводим первую страницу
      if ($id === Null) $id=1;

        $pageModel = Page::findOne(['id' => $id, 'status' => 1]);
        $categories= Category::find()->asArray()->orderBy('name')->all();
        $last_orders= Order::find()                  
                ->with('category','orderStatus','orderCity', 'user','workForm')
                ->orderBy('added_time DESC')
                ->asArray()->limit(5)->all();
        //debug($last_orders) ;

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
                        'page' => $pageModel,  // main.php
                        'categories' => $categories, 
                        'last_orders' => $last_orders,                       
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

    public function actionResetPassword()
    {
       // $this->layout = 'none';

        // try
        // {
        //     $model = new \app\models\ResetPasswordForm($token);
        // } catch(InvalidParamException $e)
        // {
        //     throw new BadRequestHttpException($e->getMessage());
        // }

        $model = new \app\models\ResetPasswordForm();
        if (Yii::$app->request->isPjax) 
        {
          //debug("Пришел Pjax");
          if($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
          {
            //debug("Пароль изменен и записан");
              Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');

              //return $this->redirect(['/page/login']);
          }else {
              Yii::$app->getSession()->setFlash('error', 'Ошибка ! Пароль изменить не удалось.');
          }
          return $this->render('resetPassword', [
                    'model' => $model,]);
        }  
            
        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }    

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        //debug("ЛОГИН");
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()))  // && $model->login()) 
        //if (Yii::$app->request->isPjax && $model->load(Yii::$app->request->post()))
        {
          //debug($model);
          if ($model->login()) 
          {       
              // "ЛОГИН прошел"; 
              $identity = Yii::$app->user->identity;
              //debug($identity);

              // запоминаем в сессии
              $session = Yii::$app->session;
              $session['identity'] = $identity;
              $wfn = WorkForm::find()->select('work_form_name')->where(['id'=>$identity['work_form_id']])->asArray()->one();
              $session['work_form_name'] = $wfn['work_form_name'];
            
              if ($identity['blk']==1){ // если стоит признак Удаления - сбрасываем его
                $identity['blk'] = 0;
                $identity['blk_date'] = Null;
                $identity->save();
              }
             //debug($identity);
             Yii::$app->getResponse()->redirect(
                  ['/cabinet/index',
                   'username' => $identity->username,
                   'isexec'  => $identity->isexec,
                  ])->send();
            return;
          }
          Yii::$app->session->setFlash('errors', "Пользователь с такой парой Email/Пароль не зарегистрирован"); 
        }
          
        Yii::$app->getResponse()->redirect('/page/frontend')->send();
        return;
        
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

    // Регистрация пользователей **********************************************
    public function actionRegistration()
    {
      // // запоминаем пользователя - Исполнитель или Заказчик
      // if (isset($_GET['isexec']))  $_SESSION['isexec'] = $_GET['isexec'];      
     
      // Загрузка аватара
      if (Yii::$app->request->isAjax) {
        if(isset($_FILES[0]['name']) && !empty($_FILES[0]['name'])) 
          {
            require_once('../libs/upload_tmp_photo.php');             
          } 
        
        //return 'Загрузили аватар';
      }

      $model = new RegForm();
      //$city = City::find()->asArray()->orderBy('name Asc')->all(); 
      $cache = \Yii::$app->cache;
      $city =  $cache->get('city');              
      $doc_list = $cache->get('doc_list');

      if (Yii::$app->request->isPjax && $model->load(Yii::$app->request->post()) && $model->check_validate())
      {  
        
        // проверяем на заполнение согласий  
          $errors = Null;
          if (!($model->personal =='yes')) $errors="- нет согласия на обработку персональных данных";
          if (!($model->agreement =='yes')) $errors .="<br>- надо принять пользовательское соглашение";

          if ($errors) {  
             Yii::$app->session->setFlash('errors', $errors);
             return $this->render('regUser', compact('model','city'));
          } 
        // проверяем на заполнение согласий  КОНЕЦ
        
        // записать данные юзера из RegForm в User и в БД
        //debug($model);            

          $user = new User();
          $user_city = new UserCity();

          $user->work_form_id = $model->work_form_id;
          $user->username = $model->username;             
          $user->sex_id = $model->sex_id;
         
          $birthday_tr=Yii::$app->formatter->asTimestamp($model->birthday);
          $birthday_tr=Yii::$app->formatter->asDate($birthday_tr, 'yyyy-MM-dd');
          $user->birthday = $birthday_tr;

          $user->phone = $model->phone;
          $user->email = $model->email;              
          $user->isexec = $model->isexec;          
                        
          $user->setPassword($model->password);
          $user->generateAuthKey();
          //$user->generateEmailVerificationToken();
          if (isset($_SESSION['avatar'])) $user->avatar =  $_SESSION['avatar'];   
          
          //debug($user);     
          if ($user->save()){ // && $this->sendEmail($user);
            $new_id = $user->getId(); //получили id нового юзера
            $_SESSION['user_id'] = $new_id;

            //вытаскиваем введенные фотографии
              $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
              if (isset($model->imageFiles)) { 
                
                //debug($model->imageFiles);

                if ($model->upload()) {   // file is uploaded successfully ? 
                  //debug($_SESSION['doc_photo']);

                  // записываем фотографии в БД  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                  $user_doc = new UserDoc();
                  $user_doc->saveUserDoc($new_id);

                  // Записываем Юзеру признак ввода документов
                  $user->isnewdocs = 1;
                  $user->save();
                  
                } else debug("Ошибка - Фотографии не загружены");
              }

              // Записываем города пользователя  
              if (isset($model->city_id)){
                foreach($model->city_id as $c_id) {
                  $user_city = new UserCity();
                  $user_city->user_id =  $_SESSION['user_id'];
                  $user_city->city_id = $c_id;
                  $user_city->save();
                }
              }  
            //echo "В БД записано"; 
            
            // перейти к подтверждению данных  
            Yii::$app->getResponse()->redirect(
                  ['confirm-data/send-code',
                   'phone' => $model->phone,
                   'email' => $model->email,
                  ])->send();
            return; 
          }else echo "Не записано в БД";     
      }    

      return $this->render('regUser', compact('model','city','doc_list'));
    }

    public function actionEditavatar()
    {
      //return '123.jpg';
      require_once('../libs/editor.php');
      $res=editor();  // получаем новое имя аватара
      // записать в БД после регистрации
      return $res;
      
    } 

    /* Вариант восстановления пароля по инструкциям в письме
     * Requests password reset **********************************************.
     *
     * @return mixed
     */
    // public function actionRequestPasswordReset()
    // {
    //     $model = new PasswordResetRequestForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //         //debug('validate-ok');
    //         if ($model->sendEmail()) {
    //             Yii::$app->session->setFlash('success', 'Проверьте Ваш почтовый ящик - на него мы выслали инструкции для восстановления пароля.');

    //             //return $this->goHome();
    //             return $this->render('requestPasswordResetToken', [
    //                         'model' => $model,
    //                     ]);
    //         } else {
    //             Yii::$app->session->setFlash('error', 'Извините, указанный почтовый ящик не существует в БД, восстановление пароля невозможно');
    //         }
    //     }

    //     return $this->render('requestPasswordResetToken', [
    //         'model' => $model,
    //     ]);
    // }

    // Восстановление пароля по коду подтверждения и его проверка
    public function actionRequestPasswordResetToken() {

      //print_r($_POST); 
      $model = new PasswordResetRequestForm();               

      if (Yii::$app->request->isPjax) 
      {
        if ($model->load(Yii::$app->request->post()) && $model->check_validate())
        {                          
          //debug($model,1);
          $email=$model->email;
          //debug($email);
          if ($email) {   // Поле ввода телефон/почта заполнено          
          
            // Генерируем код подтверждения  Отправляем код по почте или смс
             
            $confirm_code = confirm_code(6);
            // запоминаем хешированный пароль в сессии
            $_SESSION['confirm_code'] =hash('md5', $confirm_code);

            // Определяем что передали - тел или email
            if (strpos($email, '@')>0) { // подтверждение на email
              $what_confirm = 'email'; 
              $_SESSION['what_confirm'] = 'Email';
              // запоминаем в сессии для идентификации пользователя
              $_SESSION['user_email'] = $email; 

              // отправляем код по почте
                      //$email = $_POST['email'];
                      $text = 'Введите этот код - '.$confirm_code.' в форму подтверждения';
                      send_email($email,$text);           
                  //debug($email."  ".$confirm_code);
                  //return;
                  return $this->render('requestPasswordResetToken', compact('model'));
                       
                  // отправляем код по почте Конец       
                            
            }else {                   // подтверждение на телефон
              $what_confirm = 'Телефон';
                  $_SESSION['what_confirm'] = 'Телефон'; 

              // отправляем код по смс

              $phone = $_POST['email'];
                  $text = 'Введите этот код - '.$confirm_code.' в форму подтверждения';
                  $id_sms = send_sms($phone,$text);
                  // if ($id_sms)
                  //     Yii::$app->session->setFlash('send_code', 'Сгенерирован код='.$confirm_code. ' СМС отправлено'); 
                  // else 
                  //     Yii::$app->session->setFlash('send_code', 'Сгенерирован код='.$confirm_code. ' СМС НЕ отправлено'); 
                  
                  //return;
                   return $this->render('requestPasswordResetToken');
                  
            } 
          }
          
        } else 
        {
          //debug($_POST);
          // сравниваем полученный код с отправленным
          if ($_POST['code'] && hash('md5',$_POST['code'])== $_SESSION['confirm_code']) 
            { //debug("Введенный КОД ПОДТВЕРЖДЕН"); 
              // показываем страницу ввода нового ПАРОЛЯ
              Yii::$app->getResponse()->redirect(
                  ['page/reset-password',])->send();
              return; 
            }else {
              //echo "Введенный код неверен. Введите правильный код";
              Yii::$app->session->setFlash('error_code',"Введенный код неверен. Введите правильный код"); 
              return $this->render('requestPasswordResetToken', compact('model'));  
            }            

          if ($errors) {  
             Yii::$app->session->setFlash('errors', $errors);
             return $this->render('requestPasswordResetToken', compact('model'));
          } 
        }
      }  
      return $this->render('requestPasswordResetToken', compact('model')); 
           
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword__DEL()   //($token)
    {
        // try {
        //     $model = new ResetPasswordForm($token);
        // } catch (InvalidArgumentException $e) {
        //     throw new BadRequestHttpException($e->getMessage());
        // }

        // if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
        //     Yii::$app->session->setFlash('success', 'New password saved.');

        //     return $this->goHome();            
        // }
        $model = new ResetPasswordForm();
        return $this->render('resetPassword', ['model' => $model,]);
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


}
