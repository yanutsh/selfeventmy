<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\OrderFiltrForm;
use app\models\Category;
use app\models\Subcategory;
use app\models\City;
use app\models\WorkForm;
use app\models\PaymentForm;
use app\models\Order;
use app\models\OrderCategory;
use app\models\AddOrderForm;
use app\models\OrderStatus;

require_once('../libs/convert_date_ru_en.php');
require_once('../libs/convert_date_en_ru.php');

// Контроллер ЗАКАЗЧИКА 
class CabinetController extends Controller {  
    
    public $layout = 'cabinet';    // общий шаблон для всех видов контроллера

  	// ЛК - фильтр и список заказов
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
            
            //debug($model->city_id);

            // фильтрация и определение количества заказов            
            $orders_list = Order::find()
                  ->filterWhere(['AND',                       
                      ['between', 'added_time', $date_from, $date_to],
                      ['>=', 'order_budget', $model->budget_from], 
                      ['<=', 'order_budget', $model->budget_to],
                      ['=','status_order_id', $model->order_status_id],
                      ['=','city_id', $model->city_id]
                                ])                    
                  ->with('category','orderStatus','orderCity')
                  ->orderBy('added_time DESC')
                  ->asArray()->all();  //count();

              //debug ($orders_list);    

              // Добавить фильтр по категориям
              if (!$model->category_id == "") {  // если значение фильтра установлено
                foreach ($orders_list as $key=>$order) {
                  //echo "key=".$key;
                  $include_order = false;   // признак оставления заказа в списке отфильтрованных
                  foreach($order['category'] as $cat) {
                    //echo "cat-id=".$cat['id'];
                    if ($cat['id'] == $model->category_id) {
                      $include_order = true;
                      break;                  
                    }                  
                  }
                  if (!$include_order) unset($orders_list[$key]); // удаляем заказ из списка
                }
              }      

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


    // Добавление нового заказа
    public function actionAddOrder() 
    { 
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

    public function actionGetsubcategory() 
    {       
      //$model = new AddOrderForm();

       // Если пришёл AJAX запрос
      //if (Yii::$app->request->isPjax) { 
        // Устанавливаем формат ответа JSON
        //debug('Еесть Pjax');
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$data = Yii::$app->request->post();
        //$model->load($data);

        $subcategory = Subcategory::find() ->where(['category_id' => $_GET['category_id']])->orderBy('name')->asArray()->all();

        // print_r($_GET);
        // print_r($subcategory);
        // debug(json_encode($subcategory));
        // $category = Category::find() ->orderBy('name')->asArray()->all();
        return $this->render('addOrder', compact('subcategory')); 
        //return json_encode($subcategory);
      //}
    } 

}

?>