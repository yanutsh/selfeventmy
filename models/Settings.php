<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $slogan
 * @property string $tel
 * @property string $email
 * @property string $notice_email
 * @property string $address
 * @property string $address_pickup
 * @property string $duty
 * @property string $prefix_number;
 * @property integer $register_number 
 * @property string $facebook
 * @property string $twitter
 * @property string $vkontakte
 * @property string $youtube
 * @property string $odnoklassniki
 * @property string $instagram
 * @property string $googleplus
 * @property string $bodycode 
 * @property string $footer_work
 * @property string $copyright
 * @property string $robots
 * @property string $noimage
 */
class Settings extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'address', 'duty', 'address_pickup', 'footer_work', 'copyright', 'robots' ], 'string' ],
//            [['twitter', 'vkontakte', 'odnoklassniki', 'googleplus'], 'required'],
            [ [ 'logo', 'slogan', 'email', 'notice_email', 'facebook', 'twitter', 'vkontakte', 'odnoklassniki', 'googleplus', 'youtube', 'instagram' ], 'string', 'max' => 255 ],
            [ [ 'tel' ], 'string', 'max' => 32 ],
            [ 'bodycode', 'string' ],
            [ [ 'prefix_number' ], 'string', 'max' => 16 ],
            [ [ 'register_number' ], 'integer' ],
            [ [ 'name' ], 'string' ],
            [ [ 'noimage' ], 'string', 'max' => 1024 ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'name'            => 'Название сайта',
            'logo'            => 'Логотип',
            'slogan'          => 'Слоган',
            'tel'             => 'Телефон',
            'email'           => 'E-mail',
            'notice_email'    => 'E-mail для уведомлений',
            'address'         => 'Адрес магазина',
            'address_pickup'  => 'Адрес для самовывоза',
            'duty'            => 'Режим работы',
            'prefix_number'   => 'Префикс',
            'register_number' => 'Текущий регистрационный номер',
            'facebook'        => 'Facebook',
            'twitter'         => 'Twitter',
            'vkontakte'       => 'VK',
            'youtube'         => 'YouTube',
            'odnoklassniki'   => 'Odnoklassniki',
            'instagram'       => 'Instagram',
            'googleplus'      => 'Google+',
            'bodycode'        => 'Код в теге body (счетчики и пр.)',
            'footer_work'     => 'Контакты в подвале',
            'copyright'       => 'Copyright',
            'robots'          => 'Robots.txt',
            'noimage'         => 'Изображение "Нет изображения"',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->robots = file_get_contents(\Yii::getAlias('@webroot/robots.txt'));
    }

    public function beforeSave($insert)
    {
        if( parent::beforeSave($insert) )
        {
            file_put_contents(\Yii::getAlias('@webroot/robots.txt'), $this->robots);
            return true;
        }
        return false;
    }

}
