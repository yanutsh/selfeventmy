<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
//use app\models\


class ConfirmDataController extends Controller {

	public function actionIndex()
    {
   		debug('ConfirmData/Index');
    	//return $this->redirect(['page/frontend', 'id' => 1]);
    }

    public function actionSendCode() {

    	$model = new \app\models\ConfirmDataForm();
      	if ($model->load(Yii::$app->request->post())) {

      	  //print_r($_POST);
      		
      	  //echo "<br>";	
          //debug($model);          
          //debug(Yii::$app->request->post());''
          
	        if(Yii::$app->request->isPjax){	              
	            //print_r($_POST);
	            //echo($_POST['ConfirmDataForm']['phone_email']);
	            //die;
	            if ($_POST['ConfirmDataForm']['phone_email']) {	            	
	            	// Генерируем код подтверждения
	            	
	            	// Определяем что передали - тел или email
	            	// Генерируем и Отправляем код по почте или смс
	            	$confirm_code = confirm_code(6);
	            	// отправляем код по смс
	            	// отправляем код по почте
	            	Yii::$app->mailer->compose()
					    ->setFrom('from@domain.com')
					    ->setTo('to@domain.com')
					    ->setSubject('Confirm code-Код подтверждения')
					    ->setTextBody('Введите этот код - Enter this code'.$confirm_code.' в форму подтверждения')
					    ->setHtmlBody('Введите этот код - Enter this code<b>'.$confirm_code.'</b>  форму подтверждения')
					    ->send();

					echo "Сгенерирован код=".$confirm_code. "Письмо отправлено";    
	            	die;
	            }	            
	            
	            

	             if ($_POST['ConfirmDataForm']['code']) {
	             	echo("Введен КОД подтверждения");
	             	die;
	             }
	             // сравниваем код с отправленным
	             // отмечаем в БД подтверждение телефона или мейла
	             // авторизуем пользователя.	
	            

	            if ($errors) {  
	               Yii::$app->session->setFlash('errors', $errors);
	               return $this->render('send-code', compact('model'));
	            } 
	        }    
	    	//return $this->render('send-code');  //, compact('model'));
    	}
    	return $this->render('send-code');  //, compact('model'));    
    }
}