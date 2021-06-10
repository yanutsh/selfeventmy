<?php
// получение списка новых сообщений текущего юзера
namespace app\controllers\actions;

use Yii;
use yii\base\Action;
use app\models\User;
use app\models\Dialog;
use app\models\Chat;
use yii\db\Query; 

class GetNewMessAction extends Action   
    {   
        public function run()   
        {   
	        //логика экшена располагается здесь 
	        $identity=Yii::$app->user->identity; 
	        $id = $identity->id;  //Yii::$app->user->id;
	        
	        // Количество новых сообщений  по ЧАТАМ	для Заказчика 
	        if(!$identity->isexec) {     
				$new_mess_chat_sql = "SELECT dialog.chat_id, Count(chat.exec_id) AS kol_new_mess, Min(chat.order_id) AS order_id, Min(chat.exec_id) AS exec_id, Min(chat.customer_id) AS customer_id
				FROM dialog INNER JOIN chat ON dialog.chat_id = chat.id
				WHERE (((dialog.user_id) != chat.customer_id) AND ((dialog.new)=1) AND ((chat.customer_id)=".$id."))
				GROUP BY dialog.chat_id";
			}else{
				$new_mess_chat_sql = "SELECT dialog.chat_id, Count(chat.exec_id) AS kol_new_mess, Min(chat.order_id) AS order_id, Min(chat.exec_id) AS exec_id, Min(chat.customer_id) AS customer_id
				FROM dialog INNER JOIN chat ON dialog.chat_id = chat.id
				WHERE (((dialog.user_id) != chat.exec_id) AND ((dialog.new)=1) AND ((chat.exec_id)=".$id."))
				GROUP BY dialog.chat_id";
			}

			$new_mess_chat = Dialog::findBySql($new_mess_chat_sql)->asArray()->all();
			$new_mess_chat =  change_key_new($new_mess_chat,'chat_id');
	        //debug( $new_mess_chat,0); 
	                
	        // количество Чатов с новыми сообщениями
	        $new_chats=count($new_mess_chat);	        
	        //debug ($new_chats);	        
	       
	        Yii::$app->session['kol_new_chats'] =  $new_chats;   
	        Yii::$app->session['new_mess_chat'] =  $new_mess_chat;   
	        return $new_chats;              
        }
  
    }
