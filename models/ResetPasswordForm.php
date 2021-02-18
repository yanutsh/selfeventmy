<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Login form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_repeat = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],
        ];
    }

    public function attributeLabels()
    {
        return [            
            'password' => 'Новый пароль',
            'password_repeat' => 'Повторите новый пароль',            
        ];
    }

    //***********************************************************
    // запись нового пароля в БД
    public function resetPassword()
    {
        //$user = $this->_user;
        $user = User::findOne(['email' => $_SESSION['user_email']]);
        $user->setPassword($this->password);
        //$user->removePasswordResetToken();

        return $user->save(false);
    }
    
}
