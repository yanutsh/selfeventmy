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
use app\models\ExecEvent;
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
use app\models\OrderExecForm;
use app\models\Review;
use app\models\OrderCategory;
use app\models\OrderStatus;
use app\models\OrderPhoto;
use app\models\OrderResponseForm;
use app\models\StarRating;
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

      // Если пришёл AJAX запрос (данные фильтра или отклик на заказ)
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
            
            // фильтрация и определение количества заказов 
            // Настройки фильтра по предоплате
            if ( $model->prepayment === '0')  {      // без предоплаты
                $prep_compare = "=";
                $prep_value = '0';
            }elseif ( $model->prepayment == 1) {  // c предоплатoй
                $prep_compare = ">";
                $prep_value = '0';                 
            }else{
                $prep_compare = ">=";             // любой вариант
                $prep_value = '0';
            }

            // var_dump((int)$model->order_status_id);
            // echo "<br>";
            // debug ($model->order_status_id);
            
            $query = Order::find()
              ->filterWhere(['AND',                       
                  ['between', 'added_time', $date_from, $date_to],
                  ['or', ['>=', 'budget_from', $model->budget_from], ['>=', 'budget_to', $model->budget_from] ],                 
                  ['<=', 'budget_from', $model->budget_to],
                  ['=','status_order_id', (int)$model->order_status_id],
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
            
            //debug($orders_list);

            $this->layout='contentonly';
            return [
                "data" => $count,
                "orders" => $this->render('@app/views/partials/orderslist.php', compact('orders_list', 'orderResponseForm')), //$html_list, 
                "error" => null
            ];             
        } 

met_first:
        //  первый раз открываем страницу - показываем заказы в поиске исполнителя     
        
        $sql = "SELECT yii_order.*, yii_order_status.name
                FROM yii_order INNER JOIN yii_order_status ON yii_order.status_order_id = yii_order_status.id
                WHERE ( ((yii_order_status.id)=0) AND ((yii_order.added_time) Between '".  
                convert_date_ru_en(Yii::$app->params['date_from'])."' And '".
                convert_date_ru_en(Yii::$app->params['date_to'])."') )
                ORDER BY yii_order.added_time DESC";

        $orders_list =  Order::findBySql($sql)
                  ->filterWhere(['AND',                     
                     ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              ])                
                  ->with('category','orderStatus','orderCity','chats')                
                  ->asArray()->all(); 
                
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
    public function actionChatList($tab=1) {
     
      $chat_title = "Открытые чаты по обсуждаемым заказам"; // по умолчанию

        // получить число новых сообщений из БД по чатам текущего Юзера
          Yii::$app->runAction('cabinet/get-new-mess'); 
          $new_mess_chat = Yii::$app->session['new_mess_chat'];
          //debug($new_mess_chat);

          $user_id = Yii::$app->user->identity->id;  
          $jscr = "<script> var tab=1;</script>";         
        // общий запрос
          $sqlc = "SELECT yii_chat.* FROM yii_chat"; 

        // Если пришёл PJAX запрос
        //if (Yii::$app->request->isPjax) { 
          $jscr = "<script> var tab=".$tab.";</script>";
          
          if($tab == 1) {
            goto met_first; // по умолчанию первая вкладка
          }  
          if($tab==2) {               // чаты по завершенным заказам
            $chat_title = "Открытые чаты по выполненным заказам";
            // чаты по заказам где текущий юзер - Исполнитель 
            if(Yii::$app->user->identity->isexec) {  
              $sql = $sqlc." WHERE ((chat_status=1) AND (exec_id =".$user_id.") AND (result=1))"; 
              $chat_list = Chat::findBySql($sql)
                          ->with('customer','exec', 'order','dialogs')
                          ->orderBy('chat_date DESC')->asArray()->all();              
            }else{  //чаты по заказам где текущий юзер - Заказчик 
              $sql = $sqlc." WHERE ((chat_status=1) AND (customer_id =".$user_id.") AND (result=1))";  

              $chat_list = Chat::findBySql($sql)
                          ->with('customer','exec', 'order','dialogs')
                          ->orderBy('chat_date DESC')->asArray()->all();
            }  
          } 
          if($tab==3) {               // чаты недавние
            $chat_title = "Последние 10 чатов";
            // чаты по заказам где текущий юзер - Исполнитель 
            if(Yii::$app->user->identity->isexec) {
              $sql = $sqlc." WHERE (exec_id =".$user_id.")";                               
              $chat_list = Chat::findBySql($sql)
                          ->with('customer','exec', 'order','dialogs')                         
                          ->orderBy('chat_date DESC')->asArray()->limit(10)->all(); 
            }else{
            // чаты по заказам где текущий юзер - Заказчик
              $sql = $sqlc." WHERE (customer_id =".$user_id.")"; 
              $chat_list = Chat::findBySql($sql)
                          ->with('customer','exec', 'order','dialogs')
                          ->orderBy('chat_date DESC')->asArray()->limit(10)->all(); 
              //debug($chat_list);            
            }  
          }      
          if($tab==4) {               // чаты из Архива - ВСЕ кроме открытых
            $chat_title = "Закрытые чаты";
            // чаты по заказам где текущий юзер - Исполнитель 
            if(Yii::$app->user->identity->isexec) {   
              $sql = $sqlc." WHERE (((chat_status = 0) AND (exec_id =".$user_id.")))";

              $chat_list = Chat::findBySql($sql)
                          ->with('customer','exec', 'order','dialogs')
                          ->orderBy('chat_date DESC')->asArray()->all();              
                           
            }else{
            // чаты по заказам где текущий юзер - Заказчик 
              $sql = $sqlc." WHERE ((chat_status = 0) AND (customer_id =".$user_id."))";

              $chat_list = Chat::findBySql($sql)
                          ->with('customer','exec', 'order','dialogs')
                          ->orderBy('chat_date DESC')->asArray()->all(); 
            }  
          }

          // определяем состояние (статус) заказов                    
          $cht_status = array();
          foreach($chat_list as $cht) {            
            $cht_status[$cht['id']] = cht_status($cht);
          }
          //debug( $cht_status);
     
          return $this->render('chatList', compact('chat_list','new_mess_chat','jscr','cht_status','chat_title'));
        //}

        met_first:
        // первый раз открываем страницу - показываем все рабочие чаты
        
        // чаты по незавершенным заказам где текущий юзер - Исполнитель 
        if(Yii::$app->user->identity->isexec) {         
          // заказы без тех в которых Заказчик отказался или Исполнитель 
          $sql = $sqlc." WHERE (((chat_status = 1) AND (exec_id =".$user_id."))
                        AND (result IS null) 
                        AND (exec_cancel=0))";

          $chat_list = Chat::findBySql($sql)  
                        ->with('customer','exec', 'order','dialogs')              
                        ->orderBy('chat_date DESC')
                        ->asArray()->all();                       
        }else{
        // чаты по незавершенным заказам где текущий юзер - Заказчик. Без отказавшихся
          $sql = $sqlc." WHERE ((chat_status = 1) AND (customer_id =".$user_id.")
                  AND ( result IS null)
                  AND ( exec_cancel=0) )";

          $chat_list = Chat::findBySql($sql)  
                        ->with('customer','exec', 'order','dialogs')              
                        ->orderBy('chat_date DESC')
                        ->asArray()->all();                                 
        }
        // определяем состояние (статус) заказов
          //debug($chat_list,0) ;             
          $cht_status = array();                
          foreach($chat_list as $cht) {            
             $cht_status[$cht['id']] = cht_status($cht);
          }
          //debug($cht_status);        
        
        return $this->render('chatList', compact('chat_list','new_mess_chat','jscr','cht_status','chat_title'));              
    }
  
    // ЛК - список Диалогов по данному чату  ********************************************
    public function actionDialogList($chat_id, $work_form_name="Физ.лицо") { 

        $user_id = Yii::$app->user->identity->id;  
          
        // получить список всех сообщений из БД по данному чату    
        // помечаем все сообщения из данного чата как прочитанные - сброс new
        $dialog = Dialog::find()->where(['and','chat_id='.$chat_id, 'new=1',['<>','user_id',$user_id] ])
                  ->all();
        foreach($dialog as $d) {
          $d->new = 0;
          $d->save();
        }

        // обновляем число новых сообщений из БД по чатам текущего Юзера
          Yii::$app->runAction('cabinet/get-new-mess'); 
          $new_mess_chat = Yii::$app->session['new_mess_chat']; 

        $chat = Chat::find()->where(['id'=>$chat_id])->one();            
        //debug($chat);
        // определяем второго участника диалога
        if ($user_id == $chat->exec_id) $user_id_2 = $chat->customer_id;
        else $user_id_2 = $chat->exec_id;  

        // дата последней активности второго юзера
        $max_date = VisitLog::find()->select(['max(update_time) as update_time'])
                    ->where(['user_id' => $user_id_2])
                    ->asArray()->one();  // последняя активность юзера  

        // ищем назначен ли этот исполнитель на этот заказ 
        if ($chat->ischoose==1) $ischoose = 1; // исполнитель выбран
        else  $ischoose = 0;                    // исполнитель не выбран            
        
        $complain = new Complain(); // жалобы

        // Если пришёл PJAX запрос
        if (Yii::$app->request->isPjax) {
          $data = Yii::$app->request->post();  
          //debug($data);

          // Нажата кнопка Выбрать исполнителем
            if($data['field_name'] == 'choose') { 
              // проверить наличие и снять предоплату, забронировать остальную сумму
              // если отмечена Безопасная сделка и денег хватает             
             

              // сохранить выбор исполнителя заказа в чате
              //debug($data['OrderExecForm'],0);
              $chat->price = $data['OrderExecForm']['price'];
              $chat->prepayment_summ = $data['OrderExecForm']['prepayment_summ'];
              $chat->safe_deal = $data['OrderExecForm']['safe_deal'];
              $chat->ischoose = 1;  // исполнитель выбран
              $chat->ischoose_time = date('Y-m-d H:i:s');
              //debug($chat);
              $chat->save();              

              // сгенерировать сообщение исполнителю
              $message = "Админ: Вы выбраны исполнителем!";
              
              // если отмечена Безопасная сделка    
              if($chat->safe_deal == 'on') { 
                $message .="<br>В рамках безопасной сделки зарезервирована сумма в размере ".($chat->price)." ₽";  //  исполнителю

                $mess = "<br>Вы внесли денежные средства в безопасную сделку: ".($chat->price-$chat->prepayment_summ)." ₽";            // на сайт              

                // если отмечена предоплата:
                if($chat->prepayment_summ) {
                  $message .="<br>Вам переведена предоплата в размере ".$chat->prepayment_summ." ₽.";        //  исполнителю       
                   
                  $mess .= "<br>Сумма предоплаты переведена исполнителю: ".$chat->prepayment_summ." ₽.";    // на сайт             
                }
                Yii::$app->session->setFlash('payment_ok', $mess); // сообщение на сайт 
              }else{ // не безопасная сделка
                  $message .="<br>Стоимость работ - ".($chat->price)." ₽";  
                  if($chat->prepayment_summ) {
                    $message .="<br>Предоплата - ".$chat->prepayment_summ." ₽.";  }      //  исполнителю   
              }
              $message .="<br>Подтвердите прием задания к выполнению.";
              $dialog = new Dialog();  // сообщение исполнителю 
              $dialog->message = $message;
              $dialog->chat_id = $chat_id;
              $dialog->user_id = $user_id;
              $dialog->save();

              goto met_first;
            } 
          
          // Исполнитель принял заказ  
            if($work_form_name=='accepted') { 
              //debug($chat) ; 
              $message = "Админ: Исполнитель принял заказ к исполнению;";
              $dialog = new Dialog();  // сообщение исполнителю 
              $dialog->message = $message;
              $dialog->chat_id = $chat_id;
              $dialog->user_id = $user_id;
              $dialog->save(); 

              // записываем - принято к исполнению
              $chat->isaccepted = 1;
              $chat->accepted_time = date('Y-m-d H:i:s');
              $chat->save();

              // меняем статус заказа на 1 - исполнители определены
              $order = Order::find()->where(['id'=>$chat->order_id])->one();
              $order->status_order_id = 1; // исполнители определены
              $order->save();

              // записываем событие в календарь Исполнителя.
              // если мероприятие длится 1 день:
              if($order->date_from == $order->date_to || is_null($order->date_to)) {
                $exec_event = new ExecEvent();
                $exec_event->exec_id = $user_id;
                $exec_event->event_date = $order->date_from;
                $exec_event->event_description = $order->details;
                $exec_event->save();
              }else{
                $date_var = $order->date_from; 
                while ($date_var <= $order->date_to) {
                  $exec_event = new ExecEvent();
                  $exec_event->exec_id = $user_id;
                  $exec_event->event_date = $date_var;
                  $exec_event->event_description = $order->details;
                  $exec_event->save();
                  $date_var = date("Y-m-d H:i:s", strtotime($date_var.'+ 1 days'));
                  //debug($date_var);
                } 

              }

              

              // переход на dialog-list
              goto met_first;
            } 

          // Исполнитель выполнил заказ  
            if($work_form_name=='done') { 
              //debug($chat) ; 
              $message = "Админ: Исполнитель сообщил, что Ваш заказ выполнен. Подтвердите выполнение заказа";
              $dialog = new Dialog();  // сообщение исполнителю 
              $dialog->message = $message;
              $dialog->chat_id = $chat_id;
              $dialog->user_id = $user_id;
              $dialog->save(); 

              // записываем - исполнитель выполнил
              $chat->exec_done = 1;
              $chat->exec_done_time = date('Y-m-d H:i:s');
              $chat->save();

              // переход на dialog-list
              goto met_first;
            } 
                        
          // Команда подтвердить выполнение заказа 
            if(isset($_GET['confirm']) && $_GET['confirm']=='order_confirm') {
              //debug($_GET['confirm']);
              // отмечаем выполнение заказа?? Исполнителем??
                $chat->result = 1;       // записываем - успешное окончание
                $chat->result_time = date('Y-m-d H:i:s');
                $chat->save();

                // меняем статус заказа на 2 - подтверждено выполнение заказчиком
                $order = Order::find()->where(['id'=>$chat->order_id])->one();
                $order->status_order_id = 2; // подтверждено выполнение
                $order->save();
                
              // переводим деньги Исполнителю (если безопасная сделка)

              // генерируем сообщение Исполнителю
                $message = "Админ: Заказ выполнен. Спасибо за работу.";
                if($chat->safe_deal=='on') $message .= "<br>Деньги Вам переведены - ".$chat->price." ₽";
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
                if($data['field_name'] == 'win_cancel_exec') {// Заказчик отказал
                  $chat->result = 0;       // записываем - отказ Заказчика
                  $chat->result_time = date('Y-m-d H:i:s');
                  $message = "Админ: К сожалению, заказчик отказался от ваших услуг.";

                }
                if($data['field_name'] == 'win_cancel_order') {// Исполитель отказалcя
                  $chat->exec_cancel = 1;  // записываем - отказ Исполнителя  
                  $chat->cancel_time = date('Y-m-d H:i:s');
                  
                  $message = "Админ: К сожалению, исполнитель отказался от выполнения заказа."; 
                  if( $chat->prepayment_summ > 0){
                    $message .="<br>Предоплата возвращена на ваш эккаунт и средства разблокированы" ;
                    // Вернуть предоплату
                  }
                }  
                $chat->save();

              // меняем статус заказа обратно с 1 на 0 - в поиске исполнителя
                $order = Order::find()->where(['id'=>$chat->order_id])->one();
                $order->status_order_id = 0; // в поиске исполнителя
                $order->save();
              
              // генерируем сообщение Юзеру              
                $dialog = new Dialog();  // сообщение 
                $dialog->message = $message;
                $dialog->chat_id = $chat_id;
                $dialog->user_id = $user_id;
                $dialog->save(); 
                
              // возвращаемся в диалог оставить отзыв
                goto met_first;             
            }
          
          // Команда - пожаловаться
            if($data['field_name'] == 'complain'){
              $complain = new Complain();
              $complain->load($data);
              //debug($complain);
              $complain->save();

              // флеш сообщение - Жалоба отправлена
              Yii::$app->session->setFlash('dialog_msg','Жалоба отправлена. Она рассматривается в течении 48 часов.<br>Решение будет отправлено на Ваш e-mail.');

              goto met_first;
            }

          // Команда закрыть чат
              // if($work_form_name=='cancel') {
              //     //debug("cancel") ; 
              //     //$chat = Chat::find()->where(['id'=>$chat_id])->one(); // уже есть
              //     $chat->chat_status = 0;
              //     $chat->save(); 
              //     // переход на chat-list
              //     return $this->redirect('/cabinet/chat-list');
              // } 
           
          // Если кнопки не нажимались - просто записываем сообщение в диалог
             $dialog = new Dialog();
             $dialog->message = $data['message'];
             $dialog->chat_id = $chat_id;
             $dialog->user_id = $user_id;
             $dialog->save();
             //debug($dialog);
        } 

        met_first:

        // первый раз открываем страницу - показываем все 
        // диалоги по чату 

        // определяем был ли отзыв о партнере по данному заказу
          $review = Review::find()->where(['from_user_id'=>$user_id, 'for_user_id'=> $user_id_2, 'order_id'=>$chat->order_id])
                    ->one();
          if($review) $isreview = 1;  // есть отзыв 
          else $isreview = 0;         // нет отзыва  

        // определяем была ли жалоба на партнера по данному заказу
          $complain = Complain::find()->where(['from_user_id'=>$user_id, 'for_user_id'=> $user_id_2, 'order_id'=>$chat->order_id])
                    ->one();
          if($complain) $iscomplain = 1;  // есть жалоба 
          else $iscomplain = 0;           // нет жалобы     
        
        $complain = new Complain(); // жалобы
        $order_exec_form = new OrderExecForm(); // форма при выборе исполнителя

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
        
        $kol_new_chats = Yii::$app->runAction('cabinet/get-new-mess'); 
        $new_mess_chat = Yii::$app->session['new_mess_chat']; 

      
        $chat = Chat::find()->where(['id'=>$chat_id])->asArray()->one();          
        $status = cht_status($chat);         

        // если текущий юзер - заказчик - выводим диалог с исполнителем 
        if (!Yii::$app->user->identity->isexec) {
          $is_show_exec=1; // показываем исполнителя          
          $user_category=UserCategory::find()->where(['user_id'=>$user_id_2]) 
              ->with('category')->asArray()->all();             
          
        }else{
          $is_show_exec=0; // показываем Заказчика
          //debug("Я-Исполнитель. Выводим диалог с заказчиком");
        }

        return $this->render('dialogList', compact('dialog_list','work_form_name','user_category','max_date','order','$kol_new_chats','is_show_exec','user2','order_exec_form','ischoose','complain','status','isreview','iscomplain'));
                        
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
          else $reyting_order = "ASC";                  // 0 - по возрастанию
          //debug ($reyting_order);

          //debug ($model); 
                               
            $query = User::find()            
              ->select(['yii_user.*', 'star_rating.rating_avg','yii_user_category.price_from','yii_user_category.price_to'])
              ->from(['yii_user'])
              ->join('LEFT JOIN', 'star_rating', 'star_rating.rating_id=yii_user.id')
              ->join('LEFT JOIN', 'yii_user_category', 'yii_user_category.user_id=yii_user.id')
              ->filterWhere(['AND',
                 ['isexec' => 1],                       
                 //['between', 'added_time', $date_from, $date_to],
                 // ['or', ['>=', 'budget_from', $model->budget_from], ['>=', 'budget_to', //$model->budget_from] ],                 
                 //['<=', 'budget_from', $model->budget_to],
                 ['work_form_id' => $model->work_form_id],     // по форме работы
                 ['isprepayment' => $model->prepayment],       // по предоплате
                           ])             
              ->orderBy('star_rating.rating_avg '.$reyting_order);

              // $list = $query->with('userCities','starRating')->asArray()->all();
              // debug($list);
               
            // Фильтр исполнителей по категориям услуг 
            if ($model->category_id)                    
                $query->andWhere(['yii_user.id' => UserCategory::find()->select('user_id')->andWhere(['category_id'=>  $model->category_id])]);

            // Фильтр исполнителей по городам  
            if ($model->city_id)                    
                $query->andWhere(['yii_user.id' => UserCity::find()->select('user_id')->andWhere(['city_id'=>  $model->city_id])]); 

            // Фильтр исполнителей по стоимости            
            if ($model->budget_from)              
                $query->andWhere([ '<=', 'price_from', $model->budget_from]); 

            if ($model->budget_to)              
                $query->andWhere(['or',['<=', 'price_to', $model->budget_to],  ['price_to'=>null]]); 
                            
              $exec_list = $query->with('workForm', 'category', 'userCities','starRating')
                                  ->asArray()->all();     
              $count = $query->count(); // найдено исполнителей Всего 
              //debug($exec_list);             

              // возвращаем результат запроса по классическому AJAX 
              $this->layout='contentonly';
              return [
                  "data" => $count,
                  "orders" => $this->render('@app/views/partials/execlist.php', compact('exec_list', 'model', 'city', 'min_price')),  
                  "error" => null
              ]; 

              //return $this->render('execList', compact('exec_list','model', 'category', 'city', 'work_form', 'payment_form', 'count', 'min_price'));              
    

        } //else { //  первый раз открываем страницу - показываем всех исполнителей
        
        $sql = "SELECT yii_user.*,star_rating.rating_avg
                FROM yii_user LEFT JOIN star_rating ON yii_user.id = star_rating.rating_id
                WHERE (isexec = 1)
                ORDER BY star_rating.rating_avg DESC";  

        $exec_list = User::findBySql($sql)
                ->with('workForm', 'category', 'userCities')
                // ->filtrWhere()
                ->orderBy( 'rating_avg DESC')
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
    public function actionUserCard($id) {  
      // отображение карточки Исполнителя или Заказчика
      $month_num = date('n');
      $year_num = date('Y');
     
      // Если пришёл PJAX запрос - изменение месяца
        if (Yii::$app->request->isPjax && isset($_GET['move'])){
          //debug($_GET['year'],0);
          if($_GET['move']=='prev'){
            if($_GET['month'] == 1) {
              $month_num = 12;
              $year_num = $_GET['year']-1;           
            }else {
              $month_num = $_GET['month']-1;
              $year_num = $_GET['year']; 
            }                    
          }
          if($_GET['move']=='next'){
            if($_GET['month'] == 12) {
              $month_num = 1;
              $year_num = $_GET['year']+1;           
            }else {
              $month_num = $_GET['month']+1;
              $year_num = $_GET['year']; 
            }           
          }
          // Выбрать события Исполнителя в этом месяце из БД
          // $events=array(
          //   '20.07'=> 'Свадьба в МАлиновке',
          //   '15.07'=> 'День рождения тещи',
          //   '05.08'=> 'Юбилей ветерана');
          
          goto met_first;            
        }      

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

      met_first:
      // Выбрать события Исполнителя в этом месяце
       $sql = "SELECT event_date, event_description
              FROM exec_event
              WHERE ( (exec_id =".$id.") 
                      AND (Month(event_date)=".$month_num.") 
                      AND (Year(event_date)=".$year_num.") 
                    )";

        $exec_events = ExecEvent::findBySql($sql)->asArray()->all();
        //debug($exec_events);  

        // Переформатируем массив
        $events=array();          
        foreach($exec_events as $ee) {
          $ee_date = convert_datetime_en_ru($ee['event_date']);
          $events[$ee_date['dmY']] = $ee['event_description'];
        }          
        //debug($events);          
      
      $orderResponseForm = new orderResponseForm();
      // получить данные Юзера из БД
      $user = User::find()
              ->Where(['id'=> $_GET['id']])
              ->with('category', 'subcategory', 'workForm', 'cities', 'userEducations','starRating')
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
      $sql = "SELECT yii_review.*, star_rating.rating_avg
              FROM yii_review LEFT JOIN star_rating ON yii_review.from_user_id = star_rating.rating_id
              WHERE (((yii_review.for_user_id)=".$_GET['id']."))";

      $reviews = Review::findBySql($sql)->with('fromUser')->asArray()->all();
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
      return $this->render('userCard', compact('user','orders_list','reviews', 'albums','max_date','orderResponseForm','month_num','year_num','events')); 
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

  // страница оценки юзера
  public function actionUserReview($for_user_id,$chat_id,$order_id) { 
    // for_user_id - на кого отзыв
    // chat_id - какой чат закрыть (если будем закрыввать)

    $review = new Review();

    if (Yii::$app->request->isPost) { 
        $data = Yii::$app->request->post();
        $review->load($data);
        $review->save();

        // закрываем чат ??????????????
        // $chat = Chat::find()->where(['id'=>$chat_id])->one();
        // if ($chat) {
        //   $chat->chat_status=0;
        //   $chat->save();
        // }
        return $this->redirect('/cabinet/chat-list'); 
    }    
    return $this->render('userReview', compact('review','for_user_id','order_id'));
  }

  //Предложение заказа Исполнителю
  public function actionOrderOffer($exec_id, $order_id=null){ 
     $user_id = Yii::$app->user->identity->id;

    if(!is_null($order_id)) { // есть заказ, который предложить заказчику
      // открываем чат
        $chat=new Chat();
        $chat->order_id=$order_id;
        $chat->customer_id=$user_id;
        $chat->exec_id=$exec_id;
        $chat->ischoose = 1;
        $chat->save();
        $chat_id = $chat->id; // определяем id нового чата

      // записываем предложение в диалог
        $dialog = new Dialog();
        $dialog->chat_id = $chat_id;
        $dialog->user_id = $user_id;  // написал текущий юзер
        $dialog->message = "Здравствуйте! Предлагаю Вам принять участие в выполнении моего заказа.<br>Жду ВАшего решения 24 часа.";
        $dialog->save();

      // переходим в диалог
        //return Yii::$app->getResponse()->redirect(['/cabinet/dialog-list','chat_id'=>$chat_id])
        //        ->send();
        return $this->redirect(['/cabinet/dialog-list','chat_id'=>$chat_id]);

    }    
   
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
        //$abonement = $cache->getOrSet('abonement',function()
        //    {return Abonement::find() ->orderBy('price ASC')->asArray()->all();});

    // готовим список заказов данного заказчика
    $orders_list = Order::find()->where(['user_id'=>$user_id, 'status_order_id'=>0])
                    ->with('category','orderStatus','orderCity','chats')
                    ->orderBy('added_time DESC')  
                    ->asArray()->all();
    //debug($orders_list);
    return $this->render('orderListOffer', compact('orders_list', 'category', 'city', 'work_form', 'payment_form','order_status', 'count','kol_new_chats'));
  }

  // Cписок жалоб для администратора
  public function actionComplainList(){ 
    $sql = "SELECT yii_complain.*, star_rating.rating_avg AS rating1, star_rating_1.rating_avg AS rating2
            FROM (yii_complain LEFT JOIN star_rating ON yii_complain.from_user_id = star_rating.rating_id) LEFT JOIN star_rating AS star_rating_1 ON yii_complain.for_user_id = star_rating_1.rating_id";

    $complains = Complain::findBySql($sql)->with('forUser','fromUser','order')
                  ->asArray()->all();        
    //debug($complains);              
    return $this->render('complainList',compact('complains'));
  } 


  // Функция Cron - Обновление БД по таймеру
  public function actionService(){  
    // Закрываем открытые чаты:

    // по истечению трех дней после того, как один из участников чата нажал выполнено. 
    $chat = Chat::find()->where(['and','chat_status=1',['or','result=1', 'exec_done=1']])->all();
    //debug($chat,0);
    foreach($chat as $c) {
      $last_time = max($c->result_time, $c->exec_done_time);
      if (days_from($last_time)>=3){
        $c->chat_status = 0;
        $c->save();
      }  
    }

    // по истечению 5 дней после того, как отказался Исполнитель или Заказчик 
    $chat = Chat::find()->where(['and','chat_status=1',['or','result=0', 'exec_cancel=1']])->all();
    //debug($chat,0);
    foreach($chat as $c) {
      $last_time = max($c->result_time, $c->cancel_time);
      if (days_from($last_time)>=5){
        $c->chat_status = 0;
        $c->save();
      }  
    }   
  } 


    
  // Пользовательская функция Сортировки многомерного массива По возрастанию:
  public function cmp_function($a, $b){
      return ($a['name'] > $b['name']);
  } 
  

}  

?>