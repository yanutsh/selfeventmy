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
use app\models\OrderPhoto;
use app\models\AddOrderForm;
use app\models\OrderStatus;
use yii\web\UploadedFile;
use yii\data\Pagination; 

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

            $orders_list = Order::find()
                  ->filterWhere(['AND',                       
                      ['between', 'added_time', $date_from, $date_to],
                      ['>=', 'budget_from', $model->budget_from], 
                      ['<=', 'budget_from', $model->budget_to],
                      ['=','status_order_id', $model->order_status_id],
                      ['=','city_id', $model->city_id],
                      [$prep_compare, 'prepayment', $prep_value],
                                ])
                  ->andWhere(['id' => OrderCategory::find()->select('order_id')->andWhere(['category_id'=> $model->category_id])])                                  
                  ->with('category','orderStatus','orderCity', 'orderCategory', 'workForm')
                  ->orderBy('added_time DESC')
                  ->asArray()->all();  //count();

              //debug ($orders_list);                  
             

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
              // Готовим пагинацию
              $pages = new Pagination(['totalCount' => $count]);           

              $this->layout='contentonly';
              return [
                  "data" => $count,
                  "orders" => $this->render('@app/views/partials/orderslist.php', compact('orders_list')), //$html_list, 
                  "error" => null
              ];  

           
        } //else { //  первый раз открываем страницу - показываем все заказы
          
        // $orders_list = Order::find()
        //           ->filterWhere(['AND',                     
        //             ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
        //                       ])
        //         ->with('category','orderStatus','orderCity')
        //         ->orderBy('added_time DESC')
        //         ->asArray()->all();  //count();

        // $count= count($orders_list);

        // вариант с пагинацией на главной без фильтрации
        $query = Order::find()
                  ->filterWhere(['AND',                     
                    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                              ])
                ->with('category','orderStatus','orderCity');
        
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>4]); 
        $orders_lists= $query->offset($pages->offset)
                ->orderBy('added_time DESC')
                ->limit($pages->limit)
                ->all();
           
        // echo "число записей=".$countQuery->count();
        // echo "<br>offset=".$pages->offset;
        // echo "<br>limit=".$pages->limit;

        //debug( $orders_lists);
        
        $category = Category::find() ->orderBy('name')->all();
        $city = City::find() ->orderBy('name')->all();
        $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
        $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
        $order_status = OrderStatus::find() ->orderBy('name')->all();
        //debug( $order_status);

        return $this->render('index', compact('orders_lists','model', 'category', 'city', 'work_form', 'payment_form','order_status', 'count', 'pages'));              
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
            $new_order_id = $order->saveOrder($model);
            if ($new_order_id) {
              //debug ("Заказ Записан в БД id=".$new_id);

              // записываем категории и подкатегории заказа
              $order_category = new OrderCategory();
              $order_category->saveOrderCategory($model, $new_order_id); 

              // Загружаем на сервер фотки заказа и Записываем их в БД
              $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
              //debug( $model->imageFiles);
              //debug($model);
              if (!empty( $model->imageFiles)){
                if ($model->upload()) {
                    //debug("Загрузили файлы"); // Записываем фотки в БД
                  $order_photo = new OrderPhoto();
                  $order_photo -> saveOrderPhoto($new_order_id); 
                    
                }//else debug("Не загрузили файлы");
              }//else debug ("Нет файлов для загрузки");
                  
              return $this->redirect(['/cabinet']);
              
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

    // public function actionGetsubcategory() 
    // {       
    //   $subcategory = Subcategory::find() ->where(['category_id' => $_GET['category_id']])->orderBy('name')->asArray()->all();
        
    //     return $this->render('addOrder', compact('subcategory')); 
    //     //return json_encode($subcategory);
    //   //}
    // } 

}

?>