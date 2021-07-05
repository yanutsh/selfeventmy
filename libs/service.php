<?php
    // Закрываем открытые чаты:

    // по истечению трех дней после того, как один из участников чата нажал выполнено. 
    $chat = Chat::find()->where(['and','chat_status=1',['or','result=1', 'exec_done=1']])->all();
    //debug($chat,0);
    foreach($chat as $c) {
      $last_time = max($c->result_time, $c->exec_done_time);
      if (days_from($last_time)>=3){
        $c->chat_status = 0;
        $c->save();
      }  
    }

    // по истечению 5 дней после того, как отказался Исполнитель или Заказчик 
    $chat = Chat::find()->where(['and','chat_status=1',['or','result=0', 'exec_cancel=1']])->all();
    //debug($chat,0);
    foreach($chat as $c) {
      $last_time = max($c->result_time, $c->cancel_time);
      if (days_from($last_time)>=5){
        $c->chat_status = 0;
        $c->save();
      }  
    }   
  } 
?>   