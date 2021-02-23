<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\OrderFiltrForm;
use app\models\Category;
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        // Получаем данные модели из запроса
        if ($model->load($data)) {
            //Если фильтр загрузился - определяем кол. заказов удовлетворяющих фильтру
            //debug($model);

            $count = (new \yii\db\Query())
                ->select(['id'])
                ->from('yii_orders')
                ->filterWhere(['AND', 
                    ['=','category_id', $model->category_id],
                    ['between', 'added_time', convert_date_ru_en($model->date_from), convert_date_ru_en($model->date_to)],
                    ['>=', 'order_budget', $model->budget_from], 
                    ['<=', 'order_budget', $model->budget_to]
                              ])
                ->count();

            // $count = Order::find()
            //           ->where(['category_id' => $model->category_id,
            //                     //['between', 'added_time', $model->date_from, $model->date_to],
            //                     ['between', 'order_budget', $model->budget_from, $model->budget_to],
            //                   ])
            //           ->count();

            return [
                "data" => $count,
                "error" => null
            ];          

            // return [
            //     //"data" => "<b>Успешн</b>", //$model,
            //     //"data" => "Успешно посчитали", //$model,
            //     "data" => "category=".$model->category_id." Date_from=".$model->date_from." Date_to=".$model->date_to." Budget_from=".$model->budget_from." Budget_to=".$model->budget_to." count=".$count,
            //     "error" => null
            // ];

        } else {
            // Если нет, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "error1"
            ];
        }
      } else {
        // Если это не AJAX запрос, отправляем ответ с выводом количества заказов ДО фильтрации
        $count = (new \yii\db\Query())
                ->select(['id'])
                ->from('yii_orders')
                ->filterWhere(['AND', 
                          ['=','category_id', $model->category_id],
                          //['between', 'added_time', strtotime($model->date_from), strtotime($model->date_to)],
                          ['between', 'added_time', convert_date_ru_en(Yii::$app->params['date_from']), convert_date_ru_en(Yii::$app->params['date_to'])],
                          ['between', 'order_budget', $model->budget_from, $model->budget_to]
                        ])
                ->count();
                
                // echo(' model->date_from='.strtotime($model->date_from)."<br>");
                // echo(' Время в БД======='.strtotime('2021-02-10 11:38:15')."<br>");
                // echo(' model->date_to=  '.strtotime($model->date_to));
                //debug($count);

        $category = Category::find() ->orderBy('name')->all();
        return $this->render('index', compact('model', 'category','count')); 
      } 

      //debug ($category);
      //debug ($model);

      //$category = Category::find() ->orderBy('name')->all();
      //return $this->render('index', compact('model', 'category')); 
         
    	
    	// if (isset($_SESSION['isexec']) && $_SESSION['isexec'] == 1)
   		// 	return $this->render('index', ['user' => 'Исполнитель']); 
   		//elseif (isset($_SESSION['isexec']) && $_SESSION['isexec'] == 0)
   		// 	return $this->render('index', ['user' => 'Заказчик']); 
   		// else 		  		
   		// 	return $this->render('index', ['user' => 'Не определен']); 
    }
}

?>