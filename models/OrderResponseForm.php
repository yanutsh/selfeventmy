<?php

namespace app\models;

use yii\base\Model;

/**
 * This is the model class for table "yii_order".
 *
 * 
 */
class OrderResponseForm extends Model
{    
    
    public $exec_price;
    public $exeс_prepayment;
    public $exec_message;

    public function rules()
    {
        return [
            [ 'exec_message', 'required'],
            [ 'exec_message', 'string', 'max' => 500], 
            [ 'exec_message', 'default', 'value' =>'Здравствуйте! <br> Готов взяться за ваш заказ.'], 
            [['exec_price', 'exeс_prepayment'], 'integer'], 
                     
        ];
    }        
    
    public function attributeLabels()
    {
        return [
            'exec_price' => 'Укажите вашу стоимость работ', 
            'exeс_prepayment' => 'Укажите сумму предоплаты (если надо)', 
            'exec_message' => 'Сообщение заказчику',          
        ];
    }

}    