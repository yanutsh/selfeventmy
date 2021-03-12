<?php

namespace app\models;

use yii\base\Model;



/**
 * This is the model class for table "yii_order".
 *
 * 
 */
class AddOrderForm extends Model
{    
    
    public $who_need;
    public $category_id; 
    public $subcategory_id; 
    public $city_id; 
    public $members = 100;
    public $date_from;
    public $date_to;
    
    public $details; // = "Это детали заказа";
    public $wishes;    
    public $budget_from;
    public $budget_to;
    public $order_budget;
    public $prepayment;   
    public $orderPhoto;

    //public $statusOrder_id = 2;

    public function rules()
    {
        return [
            [['who_need', 'city_id', 'date_from', 'budget_to'], 'required'],
            [['user_id', 'status_order_id', 'city_id', 'category_id','members', 'order_budget', 'budget_from', 'budget_to', 'prepayment'], 'integer'],
            [['details', 'wishes'], 'string'],
            [['city_id', 'category_id','subcategory_id','added_time', 'date_from', 'date_to'], 'safe'],
            [['who_need'], 'string', 'max' => 255],           
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Выберите категорию услуги', 
            'subcategory_id' => 'Выберите подкатегорию',  
            'status_order_id' => 'Статус заказа',
            'details' => 'Описание события',
            'added_time' => 'Added Time',
            'who_need' => 'Кто нужен *',
            'city_id' => 'Город *',
            'members' => 'Количество участников',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'wishes' => 'Пожелания',
            'order_budget' => 'Фиксированный бюджет',
            'budget_from' => 'Бюджет, от',
            'budget_to' => 'Бюджет, до *',
            'prepayment' => 'Предоплата',
        ];
    }

    
}
