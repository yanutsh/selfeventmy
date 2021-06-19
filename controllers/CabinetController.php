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
use app\models\Complain;
use app\models\Dialog;
use app\models\DocList;
use app\models\WorkForm;
use app\models\PaymentForm;
use app\models\Order;
use app\models\OrderExec;
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
        //if(isset(Yii::$app->session['kol_new_chats']) )
        //      $kol_new_chats=Yii::$app->session['kol_new_chats'];
       // else 
        $kol_new_chats = Yii::$app->runAction('cabinet/get-new-mess'); 
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
              require_once('order-response-process.php');
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
        // получить число новых сообщений из БД по чатам текущего Юзера
          Yii::$app->runAction('cabinet/get-new-mess'); 
          $new_mess_chat = Yii::$app->session['new_mess_chat'];
          //debug($new_mess_chat);

          $user_id = Yii::$app->user->identity->id;           
         
        // Если пришёл PJAX запрос
        //if (Yii::$app->request->isPjax) { 
        //   // Устанавливаем формат ответа JSON
        //   Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //   $data = Yii::$app->request->post();
        //   debug($data);

        //  ТЕСТ
        //debug($_GET['var']);
            // if ($_GET['var']=="first") $msg="первый"; 
            // return $this->render('chatList', compact('msg')); 
          
        // } //else { 

        // первый раз открываем страницу - показываем все 
        // чаты по заказам где текущий юзер - Заказчик   

        // чаты по заказам где текущий юзер - Исполнитель 
        if(Yii::$app->user->identity->isexec) {           
          
          // заказы от которых Заказчик отказался - исключить из списка чатов
          $del_chats_where = OrderExec::find()->where(['exec_id'=>$user_id, 'exec_cancel'=>1])              ->asArray()->all();
          //debug($del_chats_where,0);


          $chat_list = Chat::find()->Where(['exec_id' => $user_id, 'chat_status'=> 1])    
              ->with('customer','exec', 'order','dialogs')
              //->andWhere(['order_id' => OrderExec::find()->select('order_id')->andWhere([      
              // 'and',  ['<>','result', 1], ['=','exec_cancel', 0] ]) 
              //  ])
              ->orderBy('chat_date DESC')
              ->asArray()->all();  //count();
          //debug($chat_list);       
          // удаляем чаты с отказавшимися Исполнителями 
          
            foreach($del_chats_where as $dcw){
              foreach($chat_list as $k=>$chl){
                if ($dcw['order_id']==$chl['order_id'] &&
                    $dcw['exec_id']==$chl['exec_id']) {                    
                    unset($chat_list[$k]);
                    break;
                }                
              }
            }
          $count= count($chat_list);             
        }else{

        // чаты по заказам где текущий юзер - Заказчик 
          $chat_list = Chat::find()->Where(['and',['customer_id' => $user_id, 'chat_status'=> 1]])
                ->with('customer','exec', 'order','dialogs')
                ->orderBy('chat_date DESC')
                ->asArray()->all();  //count();

        // удаляем чаты по Заказам и Исполнителям, которым отказали
          $order_exec=OrderExec::find()->where(['result'=>0])->asArray()->all();        
          foreach($order_exec as $oe){
            foreach($chat_list as $key=>$chtl){
              if($oe['order_id']==$chtl['order_id'] && $oe['exec_id']==$chtl['exec_id']){
                unset($chat_list[$key]);
                break;
              }
            }
          }
          
          $count= count($chat_list);           
        }


        //debug( $chat_list); 
        return $this->render('chatList', compact('chat_list','new_mess_chat'));              
    }
  
    // ЛК - список Диалогов по данному чату  ********************************************
    public function actionDialogList($chat_id, $work_form_name) {  

        if($work_form_name=='cancel') { //поступила  команда закрыть чат
          //debug("cancel") ; 
          $chat = Chat::find()->where(['id'=>$chat_id])->one();
          $chat->chat_status = 0;
          $chat->save(); 
          // переход на chat-list
          return $this->redirect('/cabinet/chat-list');
        }     

        // получить список всех сообщений из БД по данному чату
        $user_id = Yii::$app->user->identity->id;        
        // определяем второго участника диалога
        $chat = Chat::find()->where(['id'=>$chat_id])->one(); 
        // помечаем все сообщения из данного чата как прочитанные - сброс new
        $dialog = Dialog::find()->where(['and','chat_id='.$chat_id, 'new=1',['<>','user_id',$user_id] ])
                  ->all();
        foreach($dialog as $d) {
          $d->new = 0;
          $d->save();
        }            
        
        if ($user_id == $chat->exec_id) $user_id_2 = $chat->customer_id;
        else $user_id_2 = $chat->exec_id;

        // дата последней активности второго юзера
        $max_date = VisitLog::find()->select(['max(update_time) as update_time'])
                    ->where(['user_id' => $user_id_2])
                    ->asArray()->one();  // последняя активность юзера  

        //$order_exec = new OrderExec();
        // ищем назначен ли этот исполнитель на этот заказ 
        $order_exec =  OrderExec::find()
                      ->where(['order_id'=>$chat->order_id, 'exec_id'=>$chat->exec_id])
                      ->one(); 
        //debug($order_exec);
        if (!empty($order_exec)) $ischoose = 1; // исполнитель выбран
        else  {
          $ischoose = 0;                    // исполнитель не выбран
          $order_exec = new OrderExec();          
        }

        $complain = new Complain(); // жалобы

        // Если пришёл PJAX запрос
        if (Yii::$app->request->isPjax) {
           $data = Yii::$app->request->post();  

          // команда подтвердить выполнение заказа 
           if(isset($_GET['confirm']) && $_GET['confirm']=='order_confirm') {
            //debug($_GET['confirm']);
            // отмечаем выполнение заказа?? Исполнителем??
              $order_exec =  OrderExec::find()
                      ->where(['order_id'=>$chat->order_id, 'exec_id'=>$chat->exec_id]) ->one(); 
              if (!empty($order_exec)) {         // исполнитель был выбран
                  $order_exec->result = 1;       // записываем - успешное окончание
                  $order_exec->save();
              }else debug('order_exec не найден');    
            // переводим деньги Исполнителю (если безопасная сделка)

            // генерируем сообщение Исполнителю
              $message = "Заказ выполнен. Спасибо за работу.";
              if($order_exec->safe_deal=='on') $message .= "<br>Деньги Вам переведены - ".$order_exec->price." ₽";
              $dialog = new Dialog();  // сообщение исполнителю 
              $dialog->message = $message;
              $dialog->chat_id = $chat_id;
              $dialog->user_id = $user_id;
              $dialog->save();            
         
            goto met_first;
           }      
                  

          // Заказчик Отказал испонителю ИЛИ Исполнитель отказался от заказа
            if($data['field_name'] == 'win_cancel_exec' || $data['field_name'] == 'win_cancel_order')
            {       
                                   
              // записываем в БД признак Отказа 
              $order_exec =  OrderExec::find()
                      ->where(['order_id'=>$data['OrderExec']['order_id'], 'exec_id'=>$data['OrderExec']['exec_id'] ])->one(); 
                     
              if (!empty($order_exec)) {         // исполнитель был выбран
                
                if($data['field_name'] == 'win_cancel_exec') // Заказчик отказал
                  $order_exec->result = 0;       // записываем - отказ Заказчика
                
                if($data['field_name'] == 'win_cancel_order') // Исполитель отказалcя
                  $order_exec->exec_cancel = 1;  // записываем - отказ Исполнителя  
                
                $order_exec->save();
              }else  {                           // исполнитель не был выбран
                $order_exec = new OrderExec();
                //debug($data);  
                $order_exec->order_id = $data['OrderExec']['order_id'];
                $order_exec->exec_id = $data['OrderExec']['exec_id'];
                $order_exec->price = 0;
                if($data['field_name'] == 'win_cancel_exec') // Заказчик отказал
                  $order_exec->result = 0;       // записываем - отказ Заказчика
                
                if($data['field_name'] == 'win_cancel_order') // Исполитель отказал
                  $order_exec->exec_cancel = 1;  // записываем - отказ Исполнителя

                //debug($order_exec);        
                $order_exec->save(); 
              } 

              // генерируем сообщение Исполнителю
              if($data['field_name'] == 'win_cancel_exec')     // Заказчик отказал
                $message = "К сожалению, заказчик отказался от ваших услуг.";
              if($data['field_name'] == 'win_cancel_order')  { // Исполитель отказал
              // генерируем сообщение Заказчику  
                $message = "К сожалению, исполнитель отказался от выполнения заказа."; 
                if( $order_exec->prepayment_summ > 0){
                  $message .="<br>Предоплата возвращена на ваш эккаунт и средства разблокированы" ;

                  // Вернуть предоплату
                }
              }  
              $dialog = new Dialog();  // сообщение исполнителю 
              $dialog->message = $message;
              $dialog->chat_id = $chat_id;
              $dialog->user_id = $user_id;
              $dialog->save();            
                           
              // возвращаемся к списку чатов
              return $this->redirect('/cabinet/chat-list');              
           }

           if($data['field_name'] == 'choose') { // Нажата кнопка Выбрать исполнителем
              // проверить наличие и снять предоплату, забронировать остальную сумму

              // если отмечена Безопасная сделка и денег хватает             
             

              // сохранить исполнителя заказа
              $order_exec -> load($data);
              $order_exec->save();
              $ischoose = 1;                    // исполнитель выбран

              // сгенерировать сообщение исполнителю
              $message = "Вы выбраны исполнителем!";
              
              // если отмечена Безопасная сделка    
              if($order_exec['safe_deal']) { 
                $message ="<br>В рамках безопасной сделки зарезервирована сумма в размере ".($order_exec['price']-$order_exec['prepayment_summ'])." ₽";  //  исполнителю

                $mess = "<br>Вы внесли денежные средства в безопасную сделку: ".($order_exec['price']-$order_exec['prepayment_summ'])." ₽";            // на сайт
              }

              // если отмечена предоплата:
              if($order_exec['prepayment_summ']) {
                $message .="<br>Вам переведена предоплата в размере ".$order_exec['prepayment_summ']." ₽<br>";        //  исполнителю       
                 
                $mess .= "<br>Сумма предоплаты переведена исполнителю: ".$order_exec['prepayment_summ']." ₽.";    // на сайт             
              }
              Yii::$app->session->setFlash('payment_ok', $mess); // сообщение на сайт 
                
              $dialog = new Dialog();  // сообщение исполнителю 
              $dialog->message = $message;
              $dialog->chat_id = $chat_id;
              $dialog->user_id = $user_id;
              $dialog->save();

              goto met_first;
           }
           //debug($data);
           // записываем сообщение в диалог
           $dialog = new Dialog();
           $dialog->message = $data['message'];
           $dialog->chat_id = $chat_id;
           $dialog->user_id = $user_id;
           $dialog->save();
           //debug($dialog);


          //  ТЕСТ
          //debug($_GET['var']);
            // if ($_GET['var']=="first") $msg="первый"; 
            // return $this->render('chatList', compact('msg')); 
          
        } //else { 

met_first:
        // первый раз открываем страницу - показываем все 
        // диалоги по чату  

        $dialog_list = Dialog::find()->Where(['and',"chat_id= $chat_id",
            ['or',"user_id = $user_id", "user_id = $user_id_2"]])            
                ->with('chat','user')
                ->orderBy('send_time ASC')
                ->asArray()->all();  //count();

        // считываем данные второго юзера        
        $user2 = User::find()->where(['id'=> $user_id_2])->asArray()->one();
        //debug($user2);

        // заказ по этому чату  
        $order=Order::find()->where(['id'=> $chat->order_id])->asArray()->one();
        //debug($order);   
        //$count= count($dialog_list); 
        // echo "chat_id=".$chat_id." user_id=".$user_id." user_id_2=".$user_id_2;
        // debug($dialog_list ); 
        // сбросить в диалогах пометку new 

        $kol_new_chats = Yii::$app->runAction('cabinet/get-new-mess'); 
        $new_mess_chat = Yii::$app->session['new_mess_chat']; 

        // если текущий юзер - заказчик - выводим диалог с исполнителем 
        if (!Yii::$app->user->identity->isexec) {
          $isexec=1;
          $user_category=UserCategory::find()->where(['user_id'=>$user_id_2]) 
              ->with('category')->asArray()->all();             
               
          return $this->render('dialogList', compact('dialog_list','work_form_name','user_category','max_date','order','$kol_new_chats','isexec','user2','order_exec','ischoose','complain'));
        }else{
          $isexec=0;
          //debug("Я-Исполнитель. Выводим диалог с заказчиком");

          return $this->render('dialogList', compact('dialog_list','work_form_name','user_category','max_date','order','$kol_new_chats','isexec','user2','order_exec','ischoose','complain'));
        }                
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

    // вывести карточку Исполнителя или Заказчика ************************************ 
    public function actionUserCard() {  
      // отображение карточки Исполнителя или Заказчика

      // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) { 
          // Устанавливаем формат ответа JSON
          //debug('Еесть Ajax');
          Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          //debug($data);
          
          // Если пришёл запрос от формы отклика на заказ - создаем чат и сообщение
          if ($data['form_name'] == 'order-response') {
              require_once('order-response-process.php');
              //goto met_first;
          }
        }     

      $orderResponseForm = new orderResponseForm();
      // получить данные Юзера из БД
      $user = User::find()
              ->Where(['id'=> $_GET['id']])
              ->with('category', 'subcategory', 'workForm', 'cities', 'userEducations')
              ->asArray()
              ->one();

      //debug($user);
             
      // для получения картинок слайдера -ЗАМЕНИТЬ НА ФОТО иЗ ПОРТФОЛИО       
      $orders_list = Order::find()
              ->Where([ 'user_id'=> $_GET['id'] ])
              ->with('category','orderStatus','orderCity', 'orderCategory', 'orderPhotos', 'workForm', 'user','chats')
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
      return $this->render('userCard', compact('user','orders_list','reviews', 'albums','max_date','orderResponseForm')); 
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

  // страница оценки исполнителя
  public function actionExecReview($exec_id,$chat_id) {    
    $review = new Review();

    if (Yii::$app->request->isPost) { 
        $data = Yii::$app->request->post();
        $review->load($data);
        //debug($review);
        $review->save();

        // закрываем чат
        //echo("Закрываем чат"); 
        //debug($chat_id);
        $chat = Chat::find()->where(['id'=>$chat_id])->one();
        if ($chat) {
          $chat->chat_status=0;
          $chat->save();
        }
        return $this->redirect('/cabinet/chat-list'); 
    }    
    return $this->render('execReview', compact('review','exec_id'));
  }
    
  // Пользовательская функция Сортировки многомерного массива По возрастанию:
  public function cmp_function($a, $b){
      return ($a['name'] > $b['name']);
  } 
  

}  

?>