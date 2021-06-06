<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\AddOrderForm;
use app\models\Album;
use app\models\Abonement;
use app\models\OrderFiltrForm;
use app\models\ExecFiltrForm;
use app\models\ExecCategory;
use app\models\Category;
use app\models\UserCategory;
use app\models\Subcategory;
use app\models\City;
use app\models\Chat;
use app\models\Dialog;
use app\models\DocList;
use app\models\WorkForm;
use app\models\PaymentForm;
use app\models\Order;
use app\models\Review;
use app\models\OrderCategory;
use app\models\OrderStatus;
use app\models\OrderPhoto;
use app\models\OrderResponseForm;
use app\models\User;
use app\models\UserCity;
use app\models\UserEducation;
use app\models\UserAbonement;
use app\models\NotificationForm;
use app\models\VisitLog;

use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\db\Query; 

// Контроллер Личного Кабинета ------------------------------------------- - 
class CabinetController extends AppController {  
    
    public $layout = 'cabinet';    // общий шаблон для всех видов контроллера 
    // неизменные исходные данные, которые настраиваются в Админке и Кешируются
    public $category;
    public $city;
    public $work_form;
    public $payment_form;
    public $order_status; 
    public $abonement_freeze;
    public $abonement_nofreeze; 	
    
    // ЛК - фильтр и список Заказов  ***********************************************
    public function actionIndex()	
    { 
      // получить число новых сообщений из БД по заказам текущего Юзера
        Yii::$app->runAction('cabinet/get-new-mess'); 
      // получить информацию из БД и записать в кеш         
        Yii::$app->runAction('cabinet/get-data-from-cache'); 
        $user_id = Yii::$app->user->id;   
      
      $model = new OrderFiltrForm();
      $orderResponseForm = new orderResponseForm();

      // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) { 
          // Устанавливаем формат ответа JSON
          //debug('Еесть Ajax');
          Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          //debug($data);
          
          // Если пришёл запрос от формы отклика на заказ - создаем чат и сообщение
          if ($data['form_name'] == 'order-response') {          
          
            //debug($data);

            // получаем id заказчика
            $customer_id = Order::find()->select('user_id')->where(['id'=>$data['order_id']])
                          ->asArray()->one();
            //debug( $customer_id);              

            // создаем чат
            $chat = new Chat();
            $chat->order_id = $data['order_id'];
            $chat->exec_id = Yii::$app->user->id;
            $chat->customer_id = $customer_id['user_id'];
            //debug ($chat) ; 

            $chat->save();
            $chat_id = $chat->id; // определяем id нового чата
            //debug ($chat_id) ; 

            // записываем сообщение исполнителя в чат
            $dialog = new Dialog();
            $dialog->chat_id = $chat_id;
            $dialog->user_id = Yii::$app->user->id;  // написал текущий юзер

            
            $dialog->message = $data['OrderResponseForm']['exec_message'];
            if (!empty($data['OrderResponseForm']['exec_price']) || 
               !empty($data['OrderResponseForm']['exeс_prepayment'])) {
              $dialog->message .= chr(13).'Мои предварительные условия:';
            
              if(!empty($data['OrderResponseForm']['exec_price']))
                $dialog->message .= chr(13).'Стоимость - '.$data['OrderResponseForm']['exec_price'].' ₽. ';
              
              if(!empty($data['OrderResponseForm']['exeс_prepayment']))
                $dialog->message .= chr(13).'Предоплата - '.$data['OrderResponseForm']['exeс_prepayment'].' ₽';
            }
            //debug($dialog);
            
            $dialog->save();
            
            goto met_first;
          }   

          
          if ($data['data']=='reset'){ // сброс фильтра = модель не загружаем
            $date_from = convert_date_ru_en(Yii::$app->params['date_from']);
            $date_to = convert_date_ru_en(Yii::$app->params['date_to']);  
          }elseif ($model->load($data)) { // Получаем данные модели из запроса
            $date_from = convert_date_ru_en($model->date_from);
            $date_to = convert_date_ru_en($model->date_to);
          }else {
              // Если нет, отправляем ответ с сообщением об ошибке
              return [
                  "data" => null,
                  "error" => "error1"
              ];
          } 
            
            //debug($model);

            // фильтрация и определение количества заказов 
            // астройки фильтра по предоплате
            if ( $model->prepayment == 1)  {      // без предоплаты
                $prep_compare = "=";
                $prep_value = '0';
            }elseif ( $model->prepayment == 2) {  // c предоплатoй
                $prep_compare = ">=";
                $prep_value = '100';
            }else{
                $prep_compare = ">=";             // любой вариант
                $prep_value = '0';
            }

            // сброс фильтра по городам
            // if ($model['reset_city']) {
            //   $model->city_id = Null;
            //   debug($model->city_id);
            // }  

            //debug ($model); 

            $query = Order::find()
              ->filterWhere(['AND',                       
                  ['between', 'added_time', $date_from, $date_to],
                  ['or', ['>=', 'budget_from', $model->budget_from], ['>=', 'budget_to', $model->budget_from] ],                 
                  ['<=', 'budget_from', $model->budget_to],
                  ['=','status_order_id', $model->order_status_id],
                  ['in','city_id', $model->city_id],
                  [$prep_compare, 'prepayment', $prep_value],
                                      
                            ]);
               //->with('category','orderStatus','orderCity', 'orderCategory', 'workForm');

            if ($model->category_id)  
                $query->andWhere(['id' => OrderCategory::find()->select('order_id')->andWhere(['category_id'=>  $model->category_id])]);                 
                                        
            if ($model->work_form_id)  
                $query->andWhere(['user_id' => User::find()->select('id')->andWhere(['work_form_id'=>  $model->work_form_id])]);   

            // debug( $pages);
            $orders_list = $query->orderBy('added_time DESC')->all();       
            $count=$query->count(); // найдено заказов Всего
            
            //debug( $count);            

              // Фильтр по Формам работы
              // if (!$model->work_form_id == "") {  // если значение фильтра установлено
              //   foreach ($orders_list as $key=>$order) {
              //     if (!($order['workForm']['id'] == $model->work_form_id)) {
              //        unset($orders_list[$key]); // удаляем заказ из списка               
              //     }                  
              //   }                  
              // }
                 

              $this->layout='contentonly';
              return [
                  "data" => $count,
                  "orders" => $this->render('@app/views/partials/orderslist.php', compact('orders_list', 'orderResponseForm')), //$html_list, 
                  "error" => null
              ];             
        } 

met_first:
        //  первый раз открываем страницу - показываем все заказы        

        $orders_list = Order::find()
                  ->filterWhere(['AND',                     
                    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              ])
                ->with('category','orderStatus','orderCity','chats')                
                ->orderBy('added_time DESC')
                ->asArray()->all();  //count();
        //debug( $orders_list);  
              
        $count= count($orders_list);            
               
        // получение неизменных исходные данные из кеша или БД 
        $cache = \Yii::$app->cache;
        $category = $cache->getOrSet('category',function()
            {return Category::find() ->orderBy('name')->asArray()->all();});
        $city = $cache->getOrSet('city',function()
            {return City::find() ->orderBy('name')->asArray()->all();});
        $work_form = $cache->getOrSet('work_form',function()
            {return WorkForm::find() ->orderBy('work_form_name')->asArray()->all();});
        $payment_form = $cache->getOrSet('payment_form',function()
            {return PaymentForm::find() ->orderBy('payment_name')->asArray()->all();});
        $order_status = $cache->getOrSet('order_status',function()
            {return OrderStatus::find() ->orderBy('name')->asArray()->all();});
        $abonement = $cache->getOrSet('abonement',function()
            {return Abonement::find() ->orderBy('price ASC')->asArray()->all();});  
        //debug( $abonement);

        return $this->render('index', compact('orders_list','model', 'category', 'city', 'work_form', 'payment_form','order_status', 'count','kol_new_chats','orderResponseForm'));
                   
    }
    
    // ЛК - список Чатов  ********************************************
    public function actionChatList() {
        // получить число новых сообщений из БД по заказам текущего Юзера
          Yii::$app->runAction('cabinet/get-new-mess');            
        // запомнили в сессию   
        // Если пришёл PJAX запрос
        if (Yii::$app->request->isPjax) { 
        //   // Устанавливаем формат ответа JSON
        //   //debug('Еесть Ajax');
        //   Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //   $data = Yii::$app->request->post();
             //debug($_GET['var']);
            if ($_GET['var']=="first") $msg="первый"; 
            return $this->render('chatList', compact('msg')); 

        //   if ($data['data']=='reset'){ // сброс фильтра = модель не загружаем
        //     $date_from = convert_date_ru_en(Yii::$app->params['date_from']);
        //     $date_to = convert_date_ru_en(Yii::$app->params['date_to']);  
        //   }elseif ($model->load($data)) { // Получаем данные модели из запроса
        //     $date_from = convert_date_ru_en($model->date_from);
        //     $date_to = convert_date_ru_en($model->date_to);
        //   }else {
        //       // Если нет, отправляем ответ с сообщением об ошибке
        //       return [
        //           "data" => null,
        //           "error" => "error1"
        //       ];
        //   } 
            
        //   //debug($model);

        //     // фильтрация и определение количества заказов 
        //     // астройки фильтра по предоплате
        //     if ( $model->prepayment == 1)  {      // без предоплаты
        //         $prep_compare = "=";
        //         $prep_value = '0';
        //     }elseif ( $model->prepayment == 2) {  // c предоплатoй
        //         $prep_compare = ">=";
        //         $prep_value = '100';
        //     }else{
        //         $prep_compare = ">=";             // любой вариант
        //         $prep_value = '0';
        //     } 

        //     //debug ($model); 

        //     $query = User::find()
        //       ->filterWhere(['AND',
        //           ['isexec' => 1],                       
        //           //['between', 'added_time', $date_from, $date_to],
        //           ['or', ['>=', 'budget_from', $model->budget_from], ['>=', 'budget_to', $model->budget_from] ],                 
        //           ['<=', 'budget_from', $model->budget_to],
        //           ['work_form_id' => $model->work_form_id],
        //          // ['in','city_id', $model->city_id],
        //          // [$prep_compare, 'prepayment', $prep_value],
                                      
        //                     ]);
        //        /////->with('category','orderStatus','orderCity', 'orderCategory', 'workForm');

        //     if ($model->category_id)  
        //         $query->andWhere(['id' => ExecCategory::find()->select('user_id')->andWhere(['category_id'=>  $model->category_id])]);                 
              
        //     // debug( $pages);
        //     $exec_list = $query->all();       
        //     $count=$query->count(); // найдено заказов Всего
        //     //debug( $count);
        //     $category = Category::find() ->orderBy('name')->all();
        //     $city = City::find() ->orderBy('name')->all();
            
        //     $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
        //     $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
        //     //$order_status = OrderStatus::find() ->orderBy('name')->all();

        //       // Фильтр по Формам работы
        //       // if (!$model->work_form_id == "") {  // если значение фильтра установлено
        //       //   foreach ($orders_list as $key=>$order) {
        //       //     if (!($order['workForm']['id'] == $model->work_form_id)) {
        //       //        unset($orders_list[$key]); // удаляем заказ из списка               
        //       //     }                  
        //       //   }                  
        //       // }
                    

        //       $count= count($exec_list); 
        //       //debug($count) ;            

        //       $this->layout='contentonly';
        //       return [
        //           "data" => $count,
        //           "orders" => $this->render('@app/views/partials/execlist.php', compact('exec_list')), //$html_list, 
        //           "error" => null
        //       ];  

           
         } //else { 

        //  первый раз открываем страницу - показываем все         
        $chat_list = Chat::find()
                //->Where(['isexec' => 1])
                
                ->with('exec', 'order')
                // ->orderBy('added_time DESC')
                ->asArray()->all();  //count();

        //$count= count($chat_list);            
        //debug( $chat_list);        
              
        return $this->render('chatList', compact('chat_list'));              
    }

    // ЛК - фильтр и список Исполнителей ********************************************
    public function actionExecutiveList() {
        $model = new ExecFiltrForm();

        // данные из кеша (БД)
        $cache = \Yii::$app->cache;
        $category = $cache->get('category');
        $city = $cache->get('city');
        $work_form = $cache->get('work_form');
        $payment_form = $cache->get('payment_form');
        //debug($payment_form); 
        
        // определяем минимальные цены по исполнителям
        $min_price  = (new Query())
            ->select(['user_id', 'MIN(price_from) as min_price_from'])
            ->from(UserCategory::tableName('yii_user_category'))            
            ->groupBy(['user_id'])
            ->all();
        //debug($min_price); 
        $min_price_new = change_key_new($min_price,'user_id');
        //debug($min_price_new); 
        
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) { 
          // Устанавливаем формат ответа JSON
          
          Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          //debug($data);

          if ($data['data']=='reset'){ // сброс фильтра = модель не загружаем
          //   $date_from = convert_date_ru_en(Yii::$app->params['date_from']);
          //   $date_to = convert_date_ru_en(Yii::$app->params['date_to']);  
          }elseif ($model->load($data)) { // Получаем данные модели из запроса
          //   $date_from = convert_date_ru_en($model->date_from);
          //   $date_to = convert_date_ru_en($model->date_to);
          }else {
              // Если нет, отправляем ответ с сообщением об ошибке
              return [
                  "data" => null,
                  "error" => "error1"
              ];
          }          
            
          //debug($model);

          if ($model['reyting']) $reyting_order="DESC"; // 1 - по убыванию
          else $reyting_order = "ASC";                     // 0 - по возрастанию
          //debug ($reyting_order);

          // фильтрация и определение количества заказов 
          // настройки фильтра по предоплате
          // if ( $model->prepayment == 0)  {      // без предоплаты
          //     $prep_compare = "=";
          //     $prep_value = '0';
          // }elseif ( $model->prepayment == 1) {  // c предоплатoй
          //     $prep_compare = ">=";
          //     $prep_value = '100';
          // }else{
          //     $prep_compare = ">=";             // любой вариант
          //     $prep_value = '0';
          // } 

            //debug ($model); 

            $query = User::find()
              ->filterWhere(['AND',
                  ['isexec' => 1],                       
                  //['between', 'added_time', $date_from, $date_to],
                  // ['or', ['>=', 'budget_from', $model->budget_from], ['>=', 'budget_to', $model->budget_from] ],                 
                  //['<=', 'budget_from', $model->budget_to],
                  ['work_form_id' => $model->work_form_id],     // по форме работы
                  ['isprepayment' => $model->prepayment],       // по предоплате
                            ])
              ->orderBy('reyting '.$reyting_order);
               
            // Фильтр исполнителей по категориям услуг 
            if ($model->category_id)                    
                $query->andWhere(['id' => UserCategory::find()->select('user_id')->andWhere(['category_id'=>  $model->category_id])]);

            // Фильтр исполнителей по городам  
            if ($model->city_id)                    
                $query->andWhere(['id' => UserCity::find()->select('user_id')->andWhere(['city_id'=>  $model->city_id])]); 

            // Фильтр исполнителей по стоимости            
            if ($model->budget_from)              
              $query->andWhere(['id' => UserCategory::find()->select('user_id')->andWhere([
                '>=', 'price_from', $model->budget_from]) ]);

            if ($model->budget_to)              
              $query->andWhere(['id' => UserCategory::find()->select('user_id')->andWhere([
                '<=', 'price_to', $model->budget_to]) ]);

            


              // Фильтр по Формам работы
              // if (!$model->work_form_id == "") {  // если значение фильтра установлено
              //   foreach ($orders_list as $key=>$order) {
              //     if (!($order['workForm']['id'] == $model->work_form_id)) {
              //        unset($orders_list[$key]); // удаляем заказ из списка               
              //     }                  
              //   }                  
              // }

              
              $exec_list = $query->all();       
              $count = $query->count(); // найдено исполнителей Всего              

              //debug($model);          

              $this->layout='contentonly';
              return [
                  "data" => $count,
                  "orders" => $this->render('@app/views/partials/execlist.php', compact('exec_list', 'model', 'city', 'min_price')),  
                  "error" => null
              ]; 
        } //else { //  первый раз открываем страницу - показываем всех исполнителей
          
        $exec_list = User::find()
                ->Where(['isexec' => 1])
                //  ->filterWhere(['AND',                     
                //    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              //])
                ->with('workForm', 'category', 'userCities')
                ->orderBy( 'reyting DESC')
                // ->orderBy('added_time DESC')
                ->asArray()->all();  //count();

        $count= count($exec_list);            
        //debug( $exec_list);        
        
        return $this->render('execList', compact('exec_list','model', 'category', 'city', 'work_form', 'payment_form', 'count', 'min_price'));              
    }

    // Добавление нового заказа  *****************************************************
    public function actionAddOrder()  { 
        $model = new AddOrderForm();

        // Если пришёл PJAX запрос
        if (Yii::$app->request->isPjax) { 
          // Устанавливаем формат ответа JSON
          //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          $model->load($data); 
                
          if ($_POST['start_record']=="1") { // Есть Команда - записать заказ в БДем по 
            // Вытаскиваем из модели нужные данные и записываем по таблицам            

            $order= new Order();
            $new_id = $order->saveOrder($model);
            if ($new_id) {
              //debug ("Заказ Записан в БД id=".$new_id);             
              
              //вытаскиваем фотографии
              $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
              if (isset($model->imageFiles)) { 
                if ($model->upload()) {   // file is uploaded successfully ? 
                  //debug($_SESSION['order_photo']);               
                  // записываем фотографии в БД
                  $order_photo = new OrderPhoto();
                  $order_photo->saveOrderPhoto($new_id);
                  //unset($_SESSION['order_photo']);
                } else debug("Ошибка - Фотографии не загружены");
              }
                
              // записываем категории и подкатегории заказа
              $order_category = new OrderCategory();
              $order_category->saveOrderCategory($model, $new_id); 

              return $this->redirect(['/cabinet']);
              // записываем фотки
            }  
            else echo "заказ НЕ записан ";

          }       
          //echo ($model['category_id']);
          //debug($model);
          //$category = Category::find() ->orderBy('name')->asArray()->all();
          $i=0;
          foreach($model['category_id'] as $cat_id) 
          {
              if (!$cat_id ==''){
                $subcategory[$i] = Subcategory::find() ->where(['category_id' => $cat_id])->orderBy('name')->asArray()->all();       
                  
              } else $subcategory[$i] = array(); 
              $i=$i+1;       
          }

           return $this->render('addOrder', compact('model','category','subcategory','city'));     //  
          //} 

            //debug($model);
        } 

        $subcategory[0] = Subcategory::find() ->orderBy('name')->asArray()->all();
        $subcategory[1] = array();
        $subcategory[2] = array();
        
        //debug ($subcategory);
        return $this->render('addOrder', compact('model','category','subcategory','city'));  
    }    
    
    // вывести карточку заказа  ******************************************************
    public function actionOrderCard() {

      // получить заказ из БД
      $order = Order::find()
              ->Where(['id'=> $_GET['id'] ])
              ->with('category','orderStatus','orderCity', 'orderCategory', 'orderPhotos', 'workForm', 'user')
              ->asArray()
              ->one();

      //debug($order);

      //Время последней активности заказчика         
      $max_date = VisitLog::find()->select(['max(update_time) as update_time'])
                    ->where(['user_id' => $order['user_id']])
                    ->asArray()->one();  // последняя активность юзера 
      // вывести карточку заказа
      return $this->render('orderCard', compact('order', 'max_date')); 
    }

    // вывести карточку Исполнителя  Заказчику****************************************
    //public function actionExecCard() {
    // вывести карточку Исполнителя или Заказчика ************************************ 
    public function actionUserCard() {  
      // отображение карточки Исполнителя или Заказчика
      // получить данные Юзера из БД
      $user = User::find()
              ->Where(['id'=> $_GET['id']])
              ->with('category', 'subcategory', 'workForm', 'cities', 'userEducations')
              ->asArray()
              ->one();

      //debug($exec);
      // для получения картинок слайдера -ЗАМЕНИТЬ НА ФОТО иЗ ПОРТФОЛИО       
      $orders_list = Order::find()
              ->Where([ 'user_id'=> $_GET['id'] ])
              ->with('category','orderStatus','orderCity', 'orderCategory', 'orderPhotos', 'workForm', 'user')
              ->asArray()
              ->all();
      //debug($orders_list);        
      //Отзывы об Исполнителе 
      $reviews=Review::find()->where([ 'for_user_id'=> $_GET['id'] ])
                ->with('fromUser')->asArray()->all();
      //debug($reviews); 

      //Альбомы Исполнителя и их фотографии
      $albums=Album::find()->where([ 'user_id'=> $_GET['id'] ])
                ->with('albumPhotos')->orderBy('album_name ASC')->asArray()->all();
      //debug($albums); 

      //Время последней активности          
      $max_date = VisitLog::find()->select(['max(update_time) as update_time'])
                    ->where(['user_id' => $_GET['id']])
                    ->asArray()->one();  // последняя активность юзера           
      //debug($max_date);
                    
      // вывести карточку Юзера
      return $this->render('userCard', compact('user','orders_list','reviews', 'albums','max_date')); 
    }

    // вывести карточку Заказчика  Исполнителю ***************************************
    public function actionUserCardOldddd() {

      // получить данные Заказчика из БД
      $user = User::find()
              ->Where(['id'=> $_GET['id']])
              ->with('category', 'workForm', 'cities')
              ->asArray()
              ->one();
      //debug($user);
              
      //Список заказов Заказчика
      $orders_list=Order::find()->where([ 'user_id'=> $_GET['id'] ])
                  ->with('category','orderStatus','orderCity')
                  ->orderBy('added_time DESC')->asArray()->all();
                 
      //Отзывы о Заказчике 
      $reviews=Review::find()->where([ 'for_user_id'=> $_GET['id'] ])
                ->with('fromUser')->asArray()->all();
      //debug($reviews); 

      // вывести карточку Заказчика
      return $this->render('userCard', compact('user','orders_list','reviews')); 
    }

    // вывести Профиль текущего Пользователя (Заказчика  или Исполнителя) *********
    public function actionUserProfile() {

      // получить данные текущего Пользователя из БД
      $identity = Yii::$app->user->identity;
      //debug ($identity['id']);

      $user = User::find()
              ->Where([ 'id'=> $identity['id'] ]) 
              ->with('category', 'subcategory', 'workForm', 'cities', 'userEducations')
              ->asArray()
              ->one();

      //debug($user);
      //Список Личных заказов пользователя 
      $orders_list=Order::find()->where([ 'user_id'=> $identity['id'] ])
                  ->with('category','orderStatus','orderCity')
                   ->orderBy('added_time DESC')->asArray()->all();

      //Отзывов о пользователе оставлено
      $reviews=Review::find()->where([ 'for_user_id'=> $identity['id'] ])
                ->with('fromUser')->asArray()->all();
      //debug($reviews); 

      //Альбомы Исполнителя и их фотографии
      $albums=Album::find()->where([ 'user_id'=> $identity['id'] ])
                ->with('albumPhotos')->orderBy('album_name ASC')->asArray()->all();
      //debug($albums);  
        
      // вывести профиль Юзера
      return $this->render('userProfile', compact('user', 'orders_list', 'reviews', 'albums')); 
    }

    // Настройка данных Текущего Юзера *******************************************************
    public function actionUserTuning() {

      // получить текущего Юзера
      $identity = Yii::$app->user->identity;
      $wfn = WorkForm::find()->select('work_form_name')->where(['id'=>$identity['work_form_id']])->asArray()->one();
      $work_form_name=$wfn['work_form_name'];
      //debug ($identity['avatar']);      
      
      // вывести страницу настроек
      return $this->render('userTuning', compact('identity', 'work_form_name')); 
    }
    
    // Настройка Уведомлений Текущего Юзера *************************************
    public function actionNotificationsTuning() {
      // получить текущего Юзера
      $identity = Yii::$app->user->identity;
      $model= new NotificationForm();

      // проверить поступление данных по Pjax
      if (Yii::$app->request->isPjax) { 
        $data = Yii::$app->request->post();
        
        foreach($_POST['NotificationForm'] as $key=>$val) {
           if (isset($val)) $model[$key]=$val;
        }
        // сохраняем в БД
        $model->save_notification($identity['id']);
       
        // возвращаемся в настройки уведомлений
        return $this->render('notificationsTuning', compact('model'));
      }
        
      // получить текущие настройки из БД       
      $user = User::find()
            ->select(['push_notif', 'show_notif', 'email_notif', 'info_notif'])
            ->where(['id'=>$identity['id']])
            ->asArray()
            ->one();

      // присвоить настройки модели      
      $model->push_notif = $user['push_notif']; 
      $model->show_notif = $user['show_notif']; 
      $model->email_notif = $user['email_notif']; 
      $model->info_notif = $user['info_notif'];           
            
      //debug($model);      
      // вывести страницу настроек уведомлений
      return $this->render('notificationsTuning', compact('model')); 
    }

    // Страница Помощь  *******************************************************/
    public function actionHelp() {
           
      // вывести страницу help
      return $this->render('help'); 
    }

    // Страница Информация о Профиле ******************************************/
    public function actionProfileInfo() {
      $cache = \Yii::$app->cache;
      $identity = Yii::$app->user->identity;      
      $user_id = $identity['id'];

      $user = USER::find()->where(['id' => $identity['id']])
              ->with('workForm','cities')->one();

      $user_subcategory = UserCategory::find()
              ->where(['user_id'=>$user_id]) 
              ->with('subcategory')->asArray()->all();
     
      $user_education = UserEducation::find()->where(['user_id'=>$user_id])->asArray()->all();
      
      // получение неизменных исходные данные из кеша или БД       
      $category = $cache->getOrSet('category', function()
            {return Category::find() ->orderBy('name')->asArray()->all();});
      $city = $cache->getOrSet('city',function()
            {return City::find() ->orderBy('name')->asArray()->all();});
            
      // подкатеагории первоначальо не показываются в модальном окне
      $subcategory = ""; //Subcategory::find()->orderBy('name ASC')->all();

      $user_city = new UserCity();
      $user_category = new UserCategory();
      //$category_model = new Category();
      //debug($user);
   
      if (Yii::$app->request->isPjax) { 
        $data = Yii::$app->request->post();         
        //debug($data); 

          if ($data['field_name'] == 'myself'){
            $user->myself = $data['User']['myself']; 
            $user->save();
            return $this->render('profileInfo', compact('user')); 
          }elseif ($data['field_name'] == 'contact'){
            $user->username = $data['User']['username']; 
            $user->save(); 
            //return $this->render('profileInfo', compact('user'));
            return $this->render('profileInfo', compact('user','city','user_city', 'category', 'user_category','subcategory','user_subcategory', 'user_education'));            
          }elseif ($data['field_name'] == 'delеte'){      //удаление/блокировка эккаунта            
            $user->blk = 1; 
            $user->blk_date=date('Y-m-d H:i:s'); 
            $user->save(); 
            return $this->render('profileInfo', compact('user'));             
          }elseif ($data['field_name'] == 'deleteinfo'){
            // сбросить сессии, куки, вывести модальное окно
            return $this->redirect('/page/logout',302); //
          }elseif ($data['field_name'] == 'city'){   // добавляем города пользователя  БД
            //debug($data);
            if (!Empty($data['UserCity']['city_id'])){
              foreach($data['UserCity']['city_id'] as $uc_id) {
                $user_city = new UserCity();
                $user_city->city_id = $uc_id;
                $user_city->user_id = $identity['id'];
                $user_city->save();
              }  
            
              // обновляем информацию о пользователе
              $user = USER::find()->where(['id' => $identity['id']])->with('workForm','cities')->one(); 
                         
              return $this->render('profileInfo', compact('user','city','user_city')); 
            }  
          }elseif ($data['field_name'] == 'category'){ //добавляем и сохраняем услуги
            //debug($data);            
            $user_category->load($data);
            
            // если нажали кнопку сохранить - сохраняем данные
            if ($data['save_actions'] == 'true') {
              //debug($data);
              $user_cat = new UserCategory();
              $user_cat->load($data);
              //debug( $user_cat);
              //Если добавляли подкатегорию - записываем в БД
              if (!Empty($user_cat['subcategory_id'])) {                  
                  //debug($user_category);
                  $user_cat->user_id = $identity['id'];
                  $user_cat->save();                                                 
              }
              // обновляем данные  
              if (!empty($user_category->category_id)) {
                $subcategory = Subcategory::find()->
                    where(['category_id'=>$user_category->category_id ])-> 
                    orderBy('name ASC')->asArray() ->all();
              }
                    
              $user_subcategory = UserCategory::find()
                    ->where(['user_id'=>$user_id]) 
                    ->with('subcategory')->asArray()->all();      
              
              // возвращаемся в профиль  и обновляем не по Pjax!!!!!!!!!!.
              return $this->refresh();      
              //return $this->render('profileInfo', compact('user','city','user_city', 'category', 'user_category','subcategory','user_subcategory')); 
            }
             
            // иначе - обновляем форму - список подкатегорий            
            $category_id = $user_category->category_id;
            $subcategory = Subcategory::find()->
                    where(['category_id'=>$category_id ])-> 
                    orderBy('name ASC')->asArray() ->all();
            
            // возвращаемся к форме           
            return $this->render('profileInfo', compact('user','city','user_city', 'category', 'user_category','subcategory','user_subcategory'));           
          }elseif ($data['field_name'] == 'add_education'){ //добавляем образование  
            
            //debug($data);
            $user_education = new UserEducation();           
            $user_education->load($data);           
            //debug($user_education);

            // если нажали кнопку сохранить - сохраняем данные
            if ($data['add_education'] == 'true') { 
              $user_education->user_id = $identity['id'];
              if(!empty($user_education->start_date))               
                $user_education->start_date = convert_date_ru_en($user_education->start_date);
              else $user_education->start_date = null;  
              //debug($user_education->start_date);
              if(!empty($user_education->end_date))
              $user_education->end_date=convert_date_ru_en($user_education->end_date);
              else $user_education->end_date = null;  

              $user_education->save();

              // обновляем данные и заносим в кеш
              $user_education = UserEducation::find()
                    ->where(['user_id'=>$user_id])->asArray()->all();
              $cache->set('user_education', $user_education);                           
            }
                          
            // возвращаемся в профиль  и обновляем не по Pjax!!!!!!!!!!.
            //return $this->refresh();      
            return $this->render('profileInfo', compact('user','city','user_city', 'category', 'user_category','subcategory','user_subcategory', 'user_education')); 
          }elseif ($data['field_name'] == 'edit_education'){ //редактируем образование  
            
            //debug($data);
            $user_education = UserEducation::findOne($data['UserEducation']['id']);          
            $user_education->load($data);
            $user_education->user_id = $identity['id'];           
            //debug($user_education);
                       
            // если нажали кнопку сохранить - сохраняем данные
            if ($data['edit_education'] == 'true') { 
              //$user_education->user_id = $identity['id'];
              if(!empty($user_education->start_date))
              $user_education->start_date=convert_date_ru_en($user_education->start_date);
              //debug($user_education->start_date);
              if(!empty($user_education->end_date))
              $user_education->end_date=convert_date_ru_en($user_education->end_date);
                        
              $user_education->save(); 
            }elseif ($data['delete_education'] == 'true') {
              if ($user_education) $res = $user_education->delete(); 
              else debug("Удалить education не удалось"); 
            }
            // обновляем данные и заносим в кеш
              $user_education = UserEducation::find()
                    ->where(['user_id'=>$user_id])->asArray()->all();
              $cache->set('user_education', $user_education);                

            // возвращаемся в профиль и обновляем по Pjax!!!!!!!!!!.             
            return $this->render('profileInfo', compact('user','city','user_city', 'category', 'user_category','subcategory','user_subcategory', 'user_education')); 
          }            
      }      

      return $this->render('profileInfo', compact('user','city','user_city', 'category', 'user_category','subcategory','user_subcategory', 'user_education')); 
    }

    // Пополнение баланса
    public function actionBalance() {
           
      // вывести страницу help
      return $this->render('balance'); 
    }

    // Удаление города пользователя ***************************************************
    public function actionDeleteUserCity() {
      if (Yii::$app->request->isPjax) { 
        $user_id = Yii::$app->user->identity->id;  
        $city_id = $_GET['city_id'];
        //echo "user_id=".$user_id." city_id=".$city_id;

        //$user_city = new UserCity();
        $user_city = UserCity::find()->where(['user_id'=>$user_id, 'city_id'=>$city_id])->one();      
        if ($user_city) $res = $user_city->delete(); 

        // обновляем информацию о пользователе и возвращаем в модальное окно Город (Pjax)
        $user = User::find()->where(['id'=>$user_id])->one();        //debug($user);

        return $this->render('profileInfo', compact('user')); 

      }else echo "нет запроса по Pjax";        
    }

    // Если есть запрос на удаление - удаляем подкатегорию услуги  ********************
    public function actionDeleteUserSubcategory() {     
      
        $user_id = Yii::$app->user->identity->id;
        $subcategory_id =$_GET['subcategory_id'];        
        //debug($user_id);
        $user_category = UserCategory::find()->
              where(['user_id'=>$user_id,
                      'subcategory_id'=>$_GET['subcategory_id']])           
              ->one();         

        if ($user_category) { 
          $category_id = $user_category['category_id']; // Вытаскиваем категорию  
          $res=$user_category->delete();                // Удаляем подкатегорию
        }
        
        // новый список подкатегорий    
        $user_subcategory = UserCategory::find()->
                //where(['user_id'=>$user_id,'category_id'=>$category_id])-> 
                where(['user_id'=>$user_id])-> 
                with('subcategory')->asArray()->all();       
        
          
          $user = new User();
          $user_city = new UserCity();
          $city[]=null;
          //debug($user);
          return $this->render('profileInfo', compact('user_subcategory','user','user_city','city'));
    }    

    // добавление вида услуг Исполнителя *********************************************
    public function actionAddUserCategory() {      
      $model = new \app\models\UserCategory();
      $category = Category::find()->orderBy('name ASC')->all();

      // если запрос пришел по Pjax
      if (Yii::$app->request->isPjax) { 
        $data = Yii::$app->request->post();
        $model->load($data); 
        //debug($model);
        //if ($model->validate()) {
          $subcategory = Subcategory::find()->where(['category_id'=>$model['category_id']])->orderBy('name ASC')->asArray()->all();
                // form inputs are valid, do something here
              //debug($subcategory);
          return $this->render('addUserCategory', compact('model','category', 'subcategory'));
        //}
      }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
              $subcategory = Subcategory::find()->where(['category_id'=>$model['category_id']])->orderBy('name ASC')->asArray()->all();
                // form inputs are valid, do something here
              //debug($subcategory);
              return $this->render('addUserCategory', compact('model','subcategory'));
              //return;
            }
        }

        
        return $this->render('addUserCategory', compact('model','category'));
    }

    // Выбор абонемента для Исполнителя *********************************************
    public function actionAbonementChoose($freeze = null) {
     
      $cache_data = Yii::$app->runAction('cabinet/get-data-from-cache'); 
      
      $user_abonement = UserAbonement::find()->where(
              ['and','user_id' => Yii::$app->user->identity->id,
              ['>','end_date', date('Y-m-d H:i:s')] ]) ->asArray()->one(); 
      
      //if($freeze) $abonement = $cache_data['abonement_freeze'];          
      //else        $abonement = $cache_data['abonement_nofreeze'];
      
      if (Yii::$app->request->isPjax) { 
          $data = Yii::$app->request->post();
          $freeze = $data['freeze'];
          //debug($data);
          if($freeze) $abonement = $cache_data['abonement_freeze'];          
          else        $abonement = $cache_data['abonement_nofreeze'];
      
          return $this->render('abonementChoose',compact('abonement','freeze','user_abonement'));
      }

      if($freeze) $abonement = $cache_data['abonement_freeze'];          
      else        $abonement = $cache_data['abonement_nofreeze'];
      return $this->render('abonementChoose',compact('abonement','freeze','user_abonement'));
    }  

  // Оплата выбранного абонемента для Исполнителя *************************************  
  public function actionAbonementPayment($id = null, $duration = 0, $freeze = null) { 
    // получить данные из кеша
    $cache_data = Yii::$app->runAction('cabinet/get-data-from-cache'); 

    // проверяем наличие действующего абонемента - оплата нового невозможна
    $user_id = Yii::$app->user->identity->id;
    $res = UserAbonement::find()->where(['and','user_id'=>$user_id, 'abonement_id'=>$id,
                                        ['>','end_date', date('Y-m-d H:i:s')] 
                                        ])->asArray()->one();
    if ($res) { //debug("Есть абонемент");
      Yii::$app->session->setFlash('msg_error', "У вас есть действующий абонемент.");
      
      if($freeze) $abonement = $cache_data['abonement_freeze'];          
      else        $abonement = $cache_data['abonement_nofreeze'];

      return $this->render('abonementChoose',compact('abonement','freeze','user_abonement'));
    }

    // если запрос пришел по Pjax
    if (Yii::$app->request->isPjax) {
      
        $data = Yii::$app->request->post();
        //if (!Empty($data)) debug($data); 

        if ($data['abonement-pay-button']=='true') { // нажато подтверждение оплаты
          
          if(-1>0) // оплата не прошла
            Yii::$app->session->setFlash('msg_error', "Не хватает денег для оплаты этого абонемента.");
          else {  // оплата прошла
            $identity = Yii::$app->user->identity;      
            $user_id = $identity['id'];

            $end_date = strtotime('+'.$duration.' days');
            $end_date = date('Y-m-d H:i:s',$end_date);
            
            // записываем абонемент в БД
            $user_abonement = new UserAbonement();
            $user_abonement->user_id = $user_id;
            $user_abonement->abonement_id = $id;
            $user_abonement->end_date = $end_date;  //Now+$duration;

            if ($user_abonement->save()) {
              // выводим страницу об успешной покупке
              Yii::$app->session->setFlash('msg_success', "Вы успешно приобрели абонемент.");              

              if ($freeze) $abonement = $cache_data['abonement_freeze'];
              else         $abonement = $cache_data['abonement_nofreeze'];

              return $this->redirect(Url::to(['abonement-choose', 'freeze'=>$freeze]) );
              //return $this->redirect('abonement-choose');
            }
          }

        }elseif($data['abonement-choose-button']=='true') { // выбрать другой абонемент
          return $this->redirect('abonement-choose');
        }       
    }

    // переходим к странице оплаты абонемента
    if(!is_null($id)) {
      $abonement = Abonement::find()->where(['id'=>$id])
                  ->asArray()->one();
      //debug($abonement);       
      return $this->render('abonementPayment',compact('abonement','freeze'));
    }else debug(" Абонемент не выбран");     
  }

  // заморозка абонемента
  public function actionAbonementFreeze($id=null, $user_id=null, $freeze_days=0) {
      $user_abonement = UserAbonement::find()->where(
            ['and','abonement_id'=>$id, 'user_id'=>$user_id,
            ['>', 'end_date', date('Y-m-d H:i:s')]
            ]) ->one();
      
      // расчет новой даты окончания      
      $end_date_1 = strtotime($user_abonement->end_date); 
      
      $end_date_2 = strtotime('+'.$freeze_days.' days', $end_date_1 );
     
      $user_abonement->abonement_status = "заморожен";
      $user_abonement->freeze_date = date('Y-m-d H:i:s');      
      $user_abonement->end_date = date('Y-m-d H:i:s',$end_date_2);       
      
      $user_abonement->save();
      
      // получить данные из кеша
      $cache_data = Yii::$app->runAction('cabinet/get-data-from-cache');
        
      $abonement = $cache_data['abonement_freeze'];
      $user_abonement = UserAbonement::find()->where(['and','user_id' => $user_id,
                        ['>', 'end_date', date('Y-m-d H:i:s')] ])
                        ->asArray()->one();

      $freeze = 'on';   // показываем только абонементы с заморозкой               
      return $this->render('abonementChoose',compact('abonement','freeze','user_abonement'));     
  }

  // Установка режима предоплаты Исполнителя
  public function actionSetPrepayment(){
    if (Yii::$app->request->isAjax) { 
        $data = Yii::$app->request->post();
        $identity = Yii::$app->user->identity;
        //debug($identity);
        if ($data['status']==1) {
          $identity['isprepayment'] = 1;         
        }else{
          $identity['isprepayment'] = 0;          
        } 
        $identity->save();
        return "Установлена предоплата";          
    }
    return"НЕ Установлена предоплата";    
  } 

  // Присвоение рейтинга ***********************************************************
  public function actionProcessStarRating(){
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/process_star_rating.php");
  } 

  // отклик Исполнителя на Заказ ****************************************************
  public function actionOrderResponse(){
    $model = new orderResponseForm();
    return $this->render('orderResponseForm',compact('model'));

  }
  
  // Пользовательская функция Сортировки многомерного массива По возрастанию:
  public function cmp_function($a, $b){
      return ($a['name'] > $b['name']);
  } 

}  

?>