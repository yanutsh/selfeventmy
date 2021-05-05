<?php 
namespace app\controllers;

use yii\web\Controller;
use yii\base\Security;

require_once('../libs/functions.php');

class MytestController extends Controller {

	public function actionIndex()
    {
   		$var='2021-03-30 21:30:48';
   		print_r (convert_datetime_en_ru($var) );
   		//return $this->render('test');
    }

    // проверка работы 2-х независимых Pjax блоков
	    public function actionMultiple()
        {
            $security = new Security();
            $randomString = $security->generateRandomString();
            $randomKey = $security->generateRandomKey();
            return $this->render('multiple', [
                'randomString' => $randomString,
                'randomKey' => $randomKey,
            ]);
        }
	    
	    public function actionString()
	    {
	        $security = new Security();
	        $randomString= $security->generateRandomString();

	        // $user['cities'][]=['name'=>"Питер", "id"=>22];
	        // $user['cities'][]=['name'=>"Выборг", "id"=>33];
	        $user['cities'][]= null;

	        //debug($user);
	        //return $this->render('multiple', [
	        return $this->render('/cabinet/profileinfo.php', [	
	            'randomString' => $randomString,
	            'user'=>$user,
	        ]);
	    }
	    
	    public function actionKey()
	    {
	        $security = new Security();
	        $randomKey = $security->generateRandomKey();

	        $user['cities'][]=['name'=>"Питер", "id"=>22];
	        $user['cities'][]=['name'=>"Выборг", "id"=>33];

	        //return $this->render('multiple', [
	        return $this->render('/cabinet/profileinfo.php', [	
	            'randomKey' => $randomKey,
	            'user'=>$user,
	        ]);
	    } 
    // проверка работы 2-х независимых Pjax блоков КОНЕЦ

}
?>