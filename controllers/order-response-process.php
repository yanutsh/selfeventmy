<?php 
// обработка заполнения формы отклика на заказ и возврата к вызвавшему view
// создание нового чата и первой записи в диалоге

use app\models\Order;
use app\models\Chat;
use app\models\Dialog;

if (Yii::$app->request->isAjax) { 
          // Устанавливаем формат ответа JSON
          //debug('Еесть Ajax');
          Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
          $data = Yii::$app->request->post();
          //debug($data);
          
          // Если пришёл запрос от формы отклика на заказ - создаем чат и сообщение
          if ($data['form_name'] == 'order-response') {          
          
            //debug($data);

            // получаем id заказчика
            $customer_id = Order::find()->select('user_id')->where(['id'=>$data['order_id']])
                          ->asArray()->one();
            //debug( $customer_id);              

            // создаем чат
            $chat = new Chat();
            $chat->order_id = $data['order_id'];
            $chat->exec_id = Yii::$app->user->id;
            $chat->customer_id = $customer_id['user_id'];
            //debug ($chat) ; 

            $chat->save();
            $chat_id = $chat->id; // определяем id нового чата
            //debug ($chat_id) ; 

            // записываем сообщение исполнителя в чат
            $dialog = new Dialog();
            $dialog->chat_id = $chat_id;
            $dialog->user_id = Yii::$app->user->id;  // написал текущий юзер

            
            $dialog->message = $data['OrderResponseForm']['exec_message'];
            if (!empty($data['OrderResponseForm']['exec_price']) || 
               !empty($data['OrderResponseForm']['exeс_prepayment'])) {
              $dialog->message .= chr(13).'Мои предварительные условия:';
            
              if(!empty($data['OrderResponseForm']['exec_price']))
                $dialog->message .= chr(13).'Стоимость - '.$data['OrderResponseForm']['exec_price'].' ₽. ';
              
              if(!empty($data['OrderResponseForm']['exeс_prepayment']))
                $dialog->message .= chr(13).'Предоплата - '.$data['OrderResponseForm']['exeс_prepayment'].' ₽';
            }           
            
            $dialog->save();
          }
      } 