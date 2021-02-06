<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class ConfirmDataController extends Controller {

	public function actionIndex()
    {
   		debug('ConfirmData/Index');
    	//return $this->redirect(['page/frontend', 'id' => 1]);
    }

    public function actionSendCode() {

    	//print_r($_POST);           
        if(Yii::$app->request->isPjax){	              
            //print_r($_POST);
            
            if ($_POST['phone_email']) {   // Поле ввода телефон/почта заполнено        	
            
            	// Генерируем код подтверждения  Отправляем код по почте или смс
            	$confirm_code = confirm_code(6);
            	// запоминаем хешированный пароль в сессии
            	$_SESSION['confirm_code'] =hash('md5', $confirm_code);

            	// Определяем что передали - тел или email
            	if (strpos($_POST['phone_email'], '@')>0) {	// либо email
            		$what_confirm = 'email'; 
            		$_SESSION['what_confirm'] = 'Email';

            		// отправляем код по почте
                		Yii::$app->mailer->compose()
    				    ->setFrom('from@domain.com')
    				    ->setTo('to@domain.com')
    				    ->setSubject('Confirm code-Код подтверждения')
    				    ->setTextBody('Введите этот код - Enter this code'.$confirm_code.' в форму подтверждения')
    				    ->setHtmlBody('Введите этот код - Enter this code<b>'.$confirm_code.'</b>  форму подтверждения')
    				    ->send();
					  
    	            	Yii::$app->session->setFlash('send_code', 'Сгенерирован код='.$confirm_code. ' Письмо отправлено');	
    	            	return; 
                    // отправляем код по почте Конец       
            		            	
            	}else {										// либо телефон
            		$what_confirm = 'Телефон';            		 
            		// отправляем код по смс
            		//echo "Отправляем смс";
            		return;
            	}	
            }          
            
            // сравниваем полученный код с отправленным
            if ($_POST['code'] && hash('md5',$_POST['code'])== $_SESSION['confirm_code']) 
            {   //echo("Введенный КОД ПОДТВЕРЖДЕН");				

                // записываем признак подтверждения телефона или email
                $user = User::findOne($_SESSION['user_id']);
                if ($_SESSION['what_confirm'] == 'Email')            
                     $user->email_confirm = 1;
                else $user->phone_confirm = 1;
                $user->save();                               

             	// авторизуем пользователя.	
                Yii::$app->user->login($user);

                // показываем страницу успешного подтверждения
                Yii::$app->getResponse()->redirect(
                    ['confirm-data/confirm-code',
                    'what_confirm' => $_SESSION['what_confirm'],                    
                    ])->send(); 
                return;
             	
             }else {
             	//echo "Введенный код неверен. Введите правильный код";
             	Yii::$app->session->setFlash('error_code',"Введенный код неверен. Введите правильный код");	
	            return; 
	        }            

            if ($errors) {  
               Yii::$app->session->setFlash('errors', $errors);
               return $this->render('send-code', compact('model'));
            } 
        }    
    	//return $this->render('send-code');  //, compact('model'));
	
        else {
			return $this->render('send-code');  //, compact('model')); 
		}   
    }

     public function actionConfirmCode() {

     	//echo "Рендерим успешное подтверждение";
     	//echo "what_confirm=".$_GET['what_confirm'];
     	return $this->render('confirm-code', ['what_confirm' => $_GET['what_confirm']]);

     }	
}