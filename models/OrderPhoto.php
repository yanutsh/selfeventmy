<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_order_photo".
 *
 * @property int $id
 * @property int $order_id
 * @property string|null $photo
 *
 * @property Order $order
 */
class OrderPhoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_order_photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'integer'],
            [['photo'], 'string', 'max' => 100],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'photo' => 'Photo',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

     public function saveOrderPhoto($order_id) {
        //debug($imageFiles);
        //debug($_SESSION['order_photo']);
        foreach ($_SESSION['order_photo'] as $fname) {   //imageFiles as $fname) {                    
                $order_photo = new OrderPhoto();               
                $order_photo->order_id = $order_id;
                $order_photo->photo = $fname;   //->name;     
                
                $order_photo->save();               
        }
        unset($_SESSION['order_photo']);
        
     }

    // Запись фотографий к заказу 
    // public function  saveOrderPhoto($photoes,$order_id){
        
    //     foreach ($photoes as $photo) {
    //         //echo "<br>photo=".$photo;
    //         $order_photo = new OrderPhoto();
    //         $order_photo->order_id = $order_id;
    //         $order_photo->photo = $photo;        
            
    //         $order_photo->save();                     
    //     }
    //     //debug ("Конец foreach");
    // }
}
