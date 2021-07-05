<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model class for table "yii_order".
 *
 * 
 */
class AddOrderForm extends Model
{    
    
    public $user_id;
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
    public $prepayment = 0;   
    
    public $imageFiles;  // класс - UploadedFile

    public $status_order_id = 0;

    public function rules()
    {
        return [
            [['who_need', 'city_id', 'date_from', 'budget_to'], 'required'],
            //[['user_id', 'status_order_id', 'city_id', 'category_id','members', 'order_budget', 'budget_from', 'budget_to', 'prepayment'], 'integer'],
            [['user_id', 'status_order_id', 'city_id', 'members', 'order_budget', 'budget_from','budget_to', 'prepayment'], 'integer'],
            [['details', 'wishes'], 'string'],
            [['city_id', 'category_id','subcategory_id','added_time', 'date_from', 'date_to', 'members', 'order_photo'], 'safe'],
            [['who_need'], 'string', 'max' => 255],

            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 6],           
            
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
            'order_photo' => 'Фотографии'
        ];
    }

    public function upload()
    {            
        if ($this->validate()) { 
            //debug($this->imageFiles);
            $_SESSION['order_photo'] = array();
            foreach ($this->imageFiles as $file) {
                $newfilename=date('YmdHis').rand(100,1000) . '.' . $file->extension;
                $file->saveAs('uploads/images/orders/' . $newfilename);
                $_SESSION['order_photo'][] = $newfilename;
            }

            return true;
        } else {            
           return false;
        }
    }

    
}
