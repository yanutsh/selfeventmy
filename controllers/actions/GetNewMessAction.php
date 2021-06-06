<?php
// получение списка новых сообщений текущего юзера
namespace app\controllers\actions;

use Yii;
use yii\base\Action;
use app\models\User;
use app\models\Dialog;
use app\models\Chat;

class GetNewMessAction extends Action   
    {   
        public function run()   
        {   
	        //логика экшена располагается здесь 
	        $identity=Yii::$app->user->identity; 
	        $id = $identity->id;  //Yii::$app->user->id;
	        //debug($id );
	        if ($identity->isexec) { // ищем диалоги по чатам, где этот исполнитель
	        	$query = Dialog::find()->where(['and','new'=>1, ['<>','user_id', $id]  ])
	          			->andWhere(['chat_id' => Chat::find()->select('id')
	          			->andWhere(['and',['exec_id'=> $id, 'chat_status'=> 1]] )]);
	        }else{
	        	$query = Dialog::find()->where(['and','new'=>1, ['<>','user_id', $id] ])
	          			->andWhere(['chat_id' => Chat::find()->select('id')
	          			->andWhere(['and',['customer_id'=> $id, 'chat_status'=> 1] ])]);
	        }  
	          //->with('customer','exec','order');        
	            
	        //$dialogs = $query->orderBy('send_time DESC')->asArray()->all();         
	        //debug($dialogs);
	        $count=$query->count(); // найдено новых сообщений 
	        Yii::$app->session['kol_new_chats'] =  $count;   
	        return $count;              
        }
  
    }
