<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Test;
use app\models\User;


class TestController extends Controller {

	public function actionIndex()
    {
   		return $this->render('test');

        // тест отправки смс сообщения
        
    	
    }
}




 ?>