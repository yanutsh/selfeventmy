<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

// Контроллер ЗАКАЗЧИКА 
class CabinetController extends Controller {

	public function actionIndex()	
    {
    	$this->layout='cabinet';
    	
    	if (isset($_SESSION['isexec']) && $_SESSION['isexec'] == 1)
   			return $this->render('index', ['user' => 'Исполнитель']); 
   		elseif (isset($_SESSION['isexec']) && $_SESSION['isexec'] == 0)
   			return $this->render('index', ['user' => 'Заказчик']); 
   		else 		  		
   			return $this->render('index', ['user' => 'Не определен']); 
    }
}

?>