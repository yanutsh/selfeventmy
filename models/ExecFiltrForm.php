<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/*********************************************
 * Форма фильтра Исполнителей в Кабинете Заказчика
 **********************************************/

class ExecFiltrForm extends Model
{
    public  $category_id;       // Категория услуг
    public  $city_id;           // Городния
    public  $date_from;         // Дата от       
    public  $date_to;           // Дата до
    public  $budget_from ;      // Бюджет от
    public  $budget_to;         // Бюджет до    
    public  $prepayment;        // Форма оплаты
    public  $work_form_id;      // Форма рпботы
      
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [                
            // ['category_id', 'default', 'value' => '1'],            
            [['category_id', 'date_from', 'date_to', 'order_status_id', 'prepayment', 'work_form_id', 'city_id'], 'safe'], 

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
            'prepayment'    => 'Форма оплаты',
            'work_form_id'     => 'Форма работы',
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
