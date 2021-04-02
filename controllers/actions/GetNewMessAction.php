<?php
// получение списка новых сообщений текущего юзера
namespace app\controllers\actions;

use Yii;
use yii\base\Action;
use app\models\User;
use app\models\Dialog;
use app\models\Order;

class GetNewMessAction extends Action   
    {   
        public function run()   
        {   
	        //логика экшена располагается здесь  
	        $id = Yii::$app->user->id;
	        //debug($id );
	        $query = Dialog::find()->where(['new'=>1])
	          ->andWhere(['order_id' => Order::find()->select('id')->andWhere(['user_id'=> $id])]);
	          //->with('order');        
	            
	        //$dialogs = $query->orderBy('send_time DESC')->asArray()->all();         
	        //debug($dialogs);
	        $count=$query->count(); // найдено новых сообщений 
	        Yii::$app->session['kol_new_chats'] =  $count;   
	        return $count;              
        }
  
    }
