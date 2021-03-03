<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/*********************************************
 * Форма фильтра заказов в Кабинете
 **********************************************/
//class RegForm extends \yii\db\ActiveRecord
class OrderFiltrForm extends Model
{
    public  $city_id;           // Городния
    public  $category_id;       // Категория услуг
    public  $date_from;         // Дата от       
    public  $date_to;           // Дата до
    public  $budget_from ;      // Бюджет от
    public  $budget_to;         // Бюджет до
    public  $payment_form;      // Форма оплаты
    public  $work_form;         // Форма рпботы
    public  $order_status_id;   // Статус заказа

    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [                
            // ['category_id', 'default', 'value' => '1'],            
            [['category_id', 'date_from', 'date_to', 'order_status_id', 'city_id'], 'safe'], 

           //[['date_from', 'date_to'], 'date'],
           // ['date_from', 'default', 'value' => date('d.m.Y')],
           // ['date_to', 'default', 'value' => "23.02.2021"],  //

           [['budget_from', 'budget_to'], 'integer'], 
           // [['budget_from', 'budget_to'], 'default', 'value' => '0'], 
        ];
    }
        
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city_id'       => 'Город',
            'category_id'   => 'Услуги',
            'date_from'     => 'Дата от...',
            'date_to'       => 'Дата до...', 
            'budget_from'   => 'Бюджет от...',
            'budget_to'     => 'Бюджет до...',
            'payment_form'  => 'Форма оплаты',
            'work_form'     => 'Форма работы',
            'order_status_id' => 'Статус заказа', 
        ];
    }

     public function check_validate()
    {
        if (!$this->validate()) {
            return null;
            //$errors = $model->errors;
            //debug($errors);
        }         
       return true;
    }
}
