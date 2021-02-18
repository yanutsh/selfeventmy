<?php
namespace app\models;

use Yii;
use yii\base\Model;
//use app\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    //public $code;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
           // ['code', 'safe'],
           // ['code','integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Введите email или телефон',
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        //debug($user);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
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
