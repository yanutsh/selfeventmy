<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Test;
use app\models\User;


class TestController extends Controller {

	public function actionIndex()
    {
   		//debug('Test/Index');
    	//return $this->redirect(['page/frontend', 'id' => 1]);
    	//$model=Test::find()->all();
    	$model=User::find()->all();
    	//debug($model);

    	// $user= new Test();
    	// $user->name = 'Галя';
    	// $user->save();

    	$user= new User();

    	$user->work_form_id = 1;
        $user->username = 'Вася222';             
        $user->sex_id = 0;
      //$user->birthday = $model->birthday;
        $user->phone = '66778899998';
        $user->email = 'yyy@mmm666.ru';              
        $user->isexec = 0;                    
        $user->password = '123ghghgh4567890';

        debug($user);
        $user->save();
    }
}




 ?>