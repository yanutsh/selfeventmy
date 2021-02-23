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
    public  $category_id;       // Категория услуг
    public  $date_from;         // Дата от         )
    public  $date_to;           // Дата до
    public  $budget_from ;    // Бюджет от
    public  $budget_to; // Бюджет до
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [                
            // ['category_id', 'default', 'value' => '1'],            
            [['category_id', 'date_from', 'date_to'], 'safe'], 

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
            'category_id' => 'Услуги',
            'date_from' => 'Дата от...',
            'date_to' => 'Дата до...', 
            'budget_from' => 'Бюджет от...',
            'budget_to' => 'Бюджет до...',

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
