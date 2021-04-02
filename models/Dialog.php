<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_dialog".
 *
 * @property int $id
 * @property int $order_id по какому заказу
 * @property int $user_id кто написал
 * @property string $message сообщение
 * @property string $send_time
 * @property int $new Новое сообщение-1
 *
 * @property Order $order
 * @property User $user
 */
class Dialog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_dialog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'message'], 'required'],
            [['order_id', 'user_id', 'new'], 'integer'],
            [['message'], 'string'],
            [['send_time'], 'safe'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'order_id'  => 'по какому заказу',
            'user_id'   => 'кто написал',
            'message'   => 'сообщение',
            'send_time' => 'Send Time',
            'new'       => 'Новое сообщение',
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
