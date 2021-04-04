<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\web\UploadedFile;

/*********************************************
 * This is the model class for table "yii_user".
 **********************************************/
//class RegForm extends \yii\db\ActiveRecord
class RegForm extends Model
{
    /**
     * {@inheritdoc}
     */
    // public static function tableName()
    // {
    //     return 'yii_user';
    // }
    //public  $id;
    public  $avatar;
    public  $work_form_id;
    public  $username;
    public  $sex_id;
    public  $birthday;
    public  $phone;
    public  $email;
    public  $isexec;
    public  $password;
    public  $password_repeat;
    //public  $auth_key;
    //public  $password_reset_token;
    //public  $status;
    //public  $created_at;
    //public  $updated_at;
    //public  $verification_token;
    public  $personal;
    public  $agreement;
    public  $city_id;
    //public  $docFiles;
    public  $imageFiles;  // класс - UploadedFile для документов
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_form_id', 'username', 'phone', 'email', 'password'], 'required'],
            [['work_form_id', 'sex_id', 'isexec'], 'integer'],
            [['birthday','city_id', 'imageFiles'], 'safe'],
            [['username', 'email', 'password'], 'string', 'max' => 255],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],

            //[['phone'], 'unique'],
            //[['phone'], 'match', 'pattern' => '/^\+\d{1}-\d{3}-\d{3}-\d{2}-\d{2}$/'],
            [['phone'], 'match', 'pattern' => '/^\d{11}$/', 'message' => 'Введите телефон в формате - 11 цифр, например, 792101234567'],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 6],  
            

           // [['password_reset_token'], 'unique'],
            //[['work_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkForm::className(), 'targetAttribute' => ['work_form_id' => 'id']],
            //[['sex'], 'exist', 'skipOnError' => true, 'targetClass' => Sex::className(), 'targetAttribute' => ['sex_id' => 'id']],
            ['sex_id', 'default', 'value' => '0'],
            [['personal', 'agreement'], 'string', 'max'=> 3],
            [['personal', 'agreement'], 'safe'],            
        ];
    }


        
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            //'photo' => 'Фотография',
            'work_form_id' => 'Форма работы',
            'username' => 'ФИО или Наименование организации',
            'sex_id' => 'Пол',
            'birthday' => 'Дата рождения',
            'phone' => 'Телефон',
            'email' => 'Email',
            'isexec' => 'Isexec',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            //'auth_key' => 'Auth Key',
            //'password_reset_token' => 'Password Reset Token',
            //'status' => 'Status',
            //'created_at' => 'Created At',
            //'updated_at' => 'Updated At',
            //'verification_token' => 'Verification Token',
            'personal' => 'Обработка перс. данных',
            'agreement' => 'Соглашение', 
            'city_id'  => 'Город'
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

    // Загрузка скринов документов
    public function upload()
    {            
        //if ($this->validate()) { 
           // debug($this->imageFiles);
            $_SESSION['doc_photo'] = array();
            foreach ($this->imageFiles as $file) {
                $newfilename=date('YmdHis').rand(100,1000) . '.' . $file->extension;
                $file->saveAs('uploads/images/docs/' . $newfilename);
                $_SESSION['doc_photo'][] = $newfilename;
            }

            return true;
        //} else {            
        //   return false;
        //}
    }
}
