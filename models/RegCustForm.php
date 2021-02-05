<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "yii_user".
 *
 * @property int $id
 * @property string|null $photo
 * @property int $work_form_id
 * @property string $username Имя
 * @property int $sex
 * @property string|null $birthday
 * @property string $phone
 * @property string $email E-mail
 * @property int $isexec
 * @property string $password Пароль
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property WorkForm $workForm
 * @property Sex $sex0
 */
//class RegCustForm extends \yii\db\ActiveRecord
class RegCustForm extends Model
{
    /**
     * {@inheritdoc}
     */
    // public static function tableName()
    // {
    //     return 'yii_user';
    // }
    //public  $id;
    //public  $photo;
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
     
    //public  $workForm;
     

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_form_id', 'username', 'phone', 'email', 'password'], 'required'],
            [['work_form_id', 'sex_id', 'isexec'], 'integer'],
            [['birthday'], 'safe'],
            [['username', 'email', 'password'], 'string', 'max' => 255],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],

            [['email'], 'unique'],
            [['email'], 'email'],

            [['phone'], 'unique'],
            //[['phone'], 'match', 'pattern' => '[0-9]{3}'],
            

           // [['password_reset_token'], 'unique'],
            //[['work_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkForm::className(), 'targetAttribute' => ['work_form_id' => 'id']],
            //[['sex'], 'exist', 'skipOnError' => true, 'targetClass' => Sex::className(), 'targetAttribute' => ['sex_id' => 'id']],
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
        ];
    }

    /**
     * Gets query for [[WorkForm]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getWorkForm()
    // {
    //     return $this->hasOne(WorkForm::className(), ['id' => 'work_form_id']);
    // }

    // *
    //  * Gets query for [[Sex0]].
    //  *
    //  * @return \yii\db\ActiveQuery
     
    // public function getSex()
    // {
    //     return $this->hasOne(Sex::className(), ['id' => 'sex_id']);
    // }
}
