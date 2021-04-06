<?php

namespace app\models;

use Yii;
use app\models\City; 

/**
 * This is the model class for table "yii_user_city".
 *
 * @property int $user_id
 * @property int $city_id
 *
 * @property User $user
 * @property FsCity $city
 */
class UserCity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_user_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['user_id', 'city_id'], 'required'],
            [['user_id'], 'required'],
            [['user_id', 'city_id'], 'integer'],
            [['user_id', 'city_id'], 'unique', 'targetAttribute' => ['user_id', 'city_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'city_id' => 'City ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }


    // запись в БД городов пользователя ************************************************
    public function saveUserCity($city_id, $user_id) {
        //debug($_SESSION['doc_photo']);
        
        //debug ("identity id=".$identity['id']);
        foreach ($city_id as $c) {   //imageFiles as $fname) {                    
                $user_city = new UserCity();              
                $user_city->user_id = $user_id;
                $user_city->city_id = $c;   //->name;     
                
                $user_city->save();               
        }
        unset($_SESSION['doc_photo']);           //debug(" НЕ записано");
   }
}
