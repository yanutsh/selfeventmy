<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * FeedbackForm is the model behind the contact form.
 */
class FeedbackForm extends Model {

    public $name;
    public $phone;
    public $email;
    public $message;
    public $security;
    public $politics;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['message'], 'string'],
            [['name', 'email'], 'string', 'max' => 1024],
            [['phone'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['message', 'security'], 'safe'],
//            [ [ 'security' ], 'default', 'value' => '' ],
            [['politics'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'name' => 'Ваше имя:',
            'phone' => 'Ваш телефон:',
            'email' => 'Ваш E-mail:',
            'message' => 'Сообщение:',
            'politics' => 'Согласен с политикой конфиденциальности',
        ];
    }

    public function attributeTypes() {
        return [
            'name' => 'string',
            'phone' => 'string',
            'email' => 'string',
            'message' => 'text',
            'security' => 'hidden',
            'politics' => 'checkbox',
        ];
    }

    public function send() {
        if ($this->validate() && ($this->security == '') && (strpos($this->message, 'http://') === false) && (strpos($this->message, 'https://') === false)) {
            Yii::$app->mailer->compose(['html' => 'feedbackForm'], ['model' => $this,])
                    ->setTo([Yii::$app->settings->get('notice_email')])
                    ->setFrom([\Yii::$app->params['serverEmail'] => Yii::$app->name])
                    ->setSubject('Заявка с сайта ' . Yii::$app->request->hostName)
                    ->send();

//            $model = new FormRequest();
//            $model->setAttributes($this->attributes);
//            $model->save();

            return true;
        }
        return false;
    }

}
