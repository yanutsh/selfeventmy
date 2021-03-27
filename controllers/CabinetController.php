<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\OrderFiltrForm;
use app\models\ExecFiltrForm;
use app\models\ExecCategory;
use app\models\Category;
use app\models\Subcategory;
use app\models\City;
use app\models\User;
use app\models\Chat;
use app\models\WorkForm;
use app\models\PaymentForm;
use app\models\Order;
use app\models\Review;
use app\models\OrderCategory;
use app\models\AddOrderForm;
use app\models\OrderStatus;
use app\models\NotificationForm;

require_once('../libs/convert_date_ru_en.php');
require_once('../libs/convert_date_en_ru.php');
require_once('../libs/convert_datetime_en_ru.php');
require_once('../libs/rdate.php');

// Контроллер ЗАКАЗЧИКА - 
class CabinetController extends Controller {  
    
    public $layout = 'cabinet';    // общий шаблон для всех видов контроллера

  	// ЛК - фильтр и список Заказов  ***********************************************
    public function actionIndex()	
    {
      	//$this->layout='cabinet';

        $model = new OrderFiltrForm();
        
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) { 
          // Устанавливаем формат ответа JSON
          //debug('Еесть Ajax');
          Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();

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
            $category = Category::find() ->orderBy('name')->all();
            $city = City::find() ->orderBy('name')->all();
            
            $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
            $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
            $order_status = OrderStatus::find() ->orderBy('name')->all();

              // Фильтр по Формам работы
              // if (!$model->work_form_id == "") {  // если значение фильтра установлено
              //   foreach ($orders_list as $key=>$order) {
              //     if (!($order['workForm']['id'] == $model->work_form_id)) {
              //        unset($orders_list[$key]); // удаляем заказ из списка               
              //     }                  
              //   }                  
              // }
                    

              $count= count($orders_list); 
              //debug($count) ;            

              $this->layout='contentonly';
              return [
                  "data" => $count,
                  "orders" => $this->render('@app/views/partials/orderslist.php', compact('orders_list')), //$html_list, 
                  "error" => null
              ];  

           
        } //else { //  первый раз открываем страницу - показываем все заказы
          
        $orders_list = Order::find()
                  ->filterWhere(['AND',                     
                    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              ])
                ->with('category','orderStatus','orderCity')
                ->orderBy('added_time DESC')
                ->asArray()->all();  //count();

        $count= count($orders_list); 
           
        //debug( $orders_list);
        
        $category = Category::find() ->orderBy('name')->all();
        $city = City::find() ->orderBy('name')->all();
        $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
        $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
        $order_status = OrderStatus::find() ->orderBy('name')->all();
        //debug( $order_status);

        return $this->render('index', compact('orders_list','model', 'category', 'city', 'work_form', 'payment_form','order_status', 'count'));              
    }

    // ЛК - список Чатов  ********************************************
    public function actionChatList() {
        //$model = new ExecFiltrForm();
        
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

           
         } //else { //  первый раз открываем страницу - показываем все заказы
          
        $chat_list = Chat::find()
                //->Where(['isexec' => 1])
                //  ->filterWhere(['AND',                     
                //    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              //])
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
        
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) { 
          // Устанавливаем формат ответа JSON
          //debug('Еесть Ajax');
          Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          //debug($data);

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

            //debug ($model); 

            $query = User::find()
              ->filterWhere(['AND',
                  ['isexec' => 1],                       
                  //['between', 'added_time', $date_from, $date_to],
                  ['or', ['>=', 'budget_from', $model->budget_from], ['>=', 'budget_to', $model->budget_from] ],                 
                  ['<=', 'budget_from', $model->budget_to],
                  ['work_form_id' => $model->work_form_id],
                 // ['in','city_id', $model->city_id],
                 // [$prep_compare, 'prepayment', $prep_value],
                                      
                            ]);
               /////->with('category','orderStatus','orderCity', 'orderCategory', 'workForm');

            if ($model->category_id)  
                $query->andWhere(['id' => ExecCategory::find()->select('user_id')->andWhere(['category_id'=>  $model->category_id])]);                 
              
            // debug( $pages);
            $exec_list = $query->all();       
            $count=$query->count(); // найдено заказов Всего
            //debug( $count);
            $category = Category::find() ->orderBy('name')->all();
            $city = City::find() ->orderBy('name')->all();
            
            $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
            $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
            //$order_status = OrderStatus::find() ->orderBy('name')->all();

              // Фильтр по Формам работы
              // if (!$model->work_form_id == "") {  // если значение фильтра установлено
              //   foreach ($orders_list as $key=>$order) {
              //     if (!($order['workForm']['id'] == $model->work_form_id)) {
              //        unset($orders_list[$key]); // удаляем заказ из списка               
              //     }                  
              //   }                  
              // }
                    

              $count= count($exec_list); 
              //debug($count) ;            

              $this->layout='contentonly';
              return [
                  "data" => $count,
                  "orders" => $this->render('@app/views/partials/execlist.php', compact('exec_list')), //$html_list, 
                  "error" => null
              ];  

           
        } //else { //  первый раз открываем страницу - показываем все заказы
          
        $exec_list = User::find()
                ->Where(['isexec' => 1])
                //  ->filterWhere(['AND',                     
                //    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              //])
                ->with('workForm', 'category')
                // ->orderBy('added_time DESC')
                ->asArray()->all();  //count();

        $count= count($exec_list);            
        //debug( $exec_list);
        
        $category = Category::find() ->orderBy('name')->all();
        $city = City::find() ->orderBy('name')->all();
        $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
        $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
        
        return $this->render('execList', compact('exec_list','model', 'category', 'city', 'work_form', 'payment_form', 'count'));              
    }

    // Добавление нового заказа  *****************************************************
    public function actionAddOrder()  { 
        $model = new AddOrderForm();

         // Если пришёл PJAX запрос
        if (Yii::$app->request->isPjax) { 
          // Устанавливаем формат ответа JSON
          //debug('Еесть Ajax');
          //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          $model->load($data);
                
          if ($_POST['start_record']=="1") { // Есть Команда - записать заказ в БДем по 
            // Вытаскиваем из модели нужные данные и записываем по таблицам
              //debug($model);
            $order= new Order();
            $new_id = $order->saveOrder($model);
            if ($new_id) {
              //debug ("Заказ Записан в БД id=".$new_id);

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
      // вывести карточку заказа
      return $this->render('orderCard', ['order' => $order]); 
    }

    // вывести карточку Исполнителя  ***********************************************
    public function actionExecCard() {

      // получить данные Исполнителя из БД
      $exec = User::find()
              ->Where(['id'=> $_GET['id']])
              ->with('category', 'workForm')
              ->asArray()
              ->one();

      //debug($exec);
      // для получения картинок слайдера        
      $order = Order::find()
              ->Where(['id'=> 50 ])
              ->with('category','orderStatus','orderCity', 'orderCategory', 'orderPhotos', 'workForm', 'user')
              ->asArray()
              ->one();

      // вывести карточку заказа
      return $this->render('execCard', ['exec' => $exec, 'order' => $order]); 
    }

    // вывести карточку текущего Пользователя (Заказчика  или Исполнителя) *********
    public function actionUserCard() {

      // получить данные текущего Пользователя из БД
      $identity = Yii::$app->user->identity;
      //debug ($identity['id']);

      $user = User::find()
              ->Where([ 'id'=> $identity['id'] ]) 
              ->with('category', 'workForm')
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
        
      // вывести карточку Юзера
      return $this->render('userCard', compact('user', 'orders_list', 'reviews')); 
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
    
    // Настройка Уведомлений Текущего Юзера *******************************************************
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

    // Страница Помощь   
    /* *******************************************************/
    public function actionHelp() {
           
      // вывести страницу help
      return $this->render('help'); 
    }

    // Пополнение баланса
    public function actionBalance() {
           
      // вывести страницу help
      return $this->render('balance'); 
    }

}

?>