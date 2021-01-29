<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;


class TestController extends Controller {

	public function actionIndex()
    {
   		debug('Test/Index');
    	//return $this->redirect(['page/frontend', 'id' => 1]);
    }
}




 ?>