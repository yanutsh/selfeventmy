<?php 
namespace app\controllers;

use yii\web\Controller;

class TestMailController extends AppController {

	public function actionIndex()
    {
   		require_once('../libs/mailtest.php');
    }
}
?>