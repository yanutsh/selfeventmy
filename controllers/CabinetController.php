<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\OrderFiltrForm;
use app\models\Category;
use app\models\WorkForm;
use app\models\PaymentForm;
use app\models\Order;

require_once('../libs/convert_date_ru_en.php');
require_once('../libs/convert_date_en_ru.php');

// Контроллер ЗАКАЗЧИКА 
class CabinetController extends Controller {  

	public function actionIndex()	
    {
    	$this->layout='cabinet';

      $model = new OrderFiltrForm();
      
      // echo "date_from=".Yii::$app->params['date_from']; 
      // echo " category_id=".$model->category_id;
      // debug("date_to=".$model->date_to);
      
      // Обрабатываем Ajax запрос - если есть      
      // Если пришёл AJAX запрос
      if (Yii::$app->request->isAjax) { 
        // Устанавливаем формат ответа JSON
        //debug('Еесть Ajax');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        // Получаем данные модели из запроса
        if ($model->load($data)) {
            //Если фильтр загрузился - определяем кол. заказов удовлетворяющих фильтру
            //debug($model);

            // фильтрация и определение количества заказов
            // $orders_list = (new \yii\db\Query())
            //     ->select(['id'])
            //     ->from('yii_orders')
          $orders_list = Order::find()
                ->filterWhere(['AND', 
                    ['=','category_id', $model->category_id],
                    ['between', 'added_time', convert_date_ru_en($model->date_from), convert_date_ru_en($model->date_to)],
                    ['>=', 'order_budget', $model->budget_from], 
                    ['<=', 'order_budget', $model->budget_to]
                              ])
                ->with('category','statusOrder')
                ->asArray()->all();  //count();

            $count= count($orders_list); 
            //debug($count) ;            

            $this->layout='contentonly';
            return [
                "data" => $count,
                "orders" => $this->render('@app/views/partials/orderslist.php', compact('orders_list')), //$html_list, 
                "error" => null
            ];  

        } else {
            // Если нет, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "error1"
            ];
        }
      } else {
        
        $orders_list = Order::find()
                  ->filterWhere(['AND', 
                    ['=','category_id', $model->category_id],
                    ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                    ['>=', 'order_budget', $model->budget_from], 
                    ['<=', 'order_budget', $model->budget_to]
                              ])
                ->with('category','statusOrder')
                ->asArray()->all();  //count();

        $count= count($orders_list); 
           
        //debug( $orders_list);
        
        $category = Category::find() ->orderBy('name')->all();
        $work_form= WorkForm::find() ->orderBy('work_form_name')->all();
        $payment_form= PaymentForm::find() ->orderBy('payment_name')->all();
        $this->view->params['model'] = $model;
        $this->view->params['category'] = $category;
        $this->view->params['work_form'] = $work_form;
        $this->view->params['payment_form'] = $payment_form;
        $this->view->params['count'] = $count;
        //$this->view->params['orders_list'] = $orders_list;

        return $this->render('index', compact('orders_list','model', 'category', 'work_form', 'payment_form','count')); 
      } 

          	
    	// if (isset($_SESSION['isexec']) && $_SESSION['isexec'] == 1)
   		// 	return $this->render('index', ['user' => 'Исполнитель']); 
   		//elseif (isset($_SESSION['isexec']) && $_SESSION['isexec'] == 0)
   		// 	return $this->render('index', ['user' => 'Заказчик']); 
   		// else 		  		
   		// 	return $this->render('index', ['user' => 'Не определен']); 
    }
}

?>