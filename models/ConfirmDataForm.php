<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ConfirmDataForm extends Model
{
    public  $phone_email;
    public  $code;
        
    public function rules()
    {
        return [
            //[['phone_email'], 'required'],            
        ];
    }        
    
    public function attributeLabels()
    {
        return [
            'phone_email' => 'Выберите телефон или почту', 
            'code' => 'Код подтверждения',           
        ];
    }
}