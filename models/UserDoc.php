<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_user_doc".
 *
 * @property int $id
 * @property int $user_id
 * @property string $photo имя фотографии
 *
 * @property User $user
 */
class UserDoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_user_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'photo'], 'required'],
            [['user_id'], 'integer'],
            [['photo'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'photo' => 'имя фотографии',
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

    public function saveUserDoc($user_id) {
        //debug($_SESSION['doc_photo']);
       
        foreach ($_SESSION['doc_photo'] as $fname) {   //imageFiles as $fname) {                    
                $user_doc = new UserDoc();              
                $user_doc->user_id = $user_id;
                $user_doc->photo = $fname;   //->name;     
                
                $user_doc->save();               
        }
        unset($_SESSION['doc_photo']);           //debug(" НЕ записано");
   }
}
