<?php 
namespace app\controllers;

use yii\web\Controller;


require_once('../libs/convert_datetime_en_ru.php');
require_once('../libs/rdate.php') ;   

class MytestController extends Controller {

	public function actionIndex()
    {
   		$var='2021-03-30 21:30:48';
   		print_r (convert_datetime_en_ru($var) );
   		//return $this->render('test');
    }
}
?>