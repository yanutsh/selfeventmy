<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User; 

/**
 * Форма для настройки уведомлений
 */
class NotificationForm extends Model
{
    public $push_notif;   //Пуш-уведомления  
    public $show_notif;   //Видимость анкеты
    public $email_notif;  //Получать письма на почту  
    public $info_notif;   //Информация о сервисе  
    //public $_csrf;
    
    /**
     * Сохранение уведомлений для данного юзера
    */
    public function save_notification($id)
    {
        $user = new USER();
        $user = USER::find()->where(['id'=>$id])->one();

        //debug($user);
        
        if ($this->push_notif) $user->push_notif = 1;
        else  $user->push_notif = 0;
        if ($this->show_notif) $user->show_notif = 1;
        else  $user->show_notif = 0;
        if ($this->email_notif) $user->email_notif = 1;
        else  $user->email_notif = 0;
        if ($this->info_notif) $user->info_notif = 1;
        else  $user->info_notif = 0;
       
        return $user->save();
    }    
}
