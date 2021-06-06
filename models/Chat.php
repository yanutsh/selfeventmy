<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property string $chat_date Время создания чата
 * @property int $order_id По какому заказу диалог
 * @property int $customer_id Заказчик
 * @property int $exec_id Мастер-Исполнитель
 * @property int $chat_status 0- закрыт 1- открыт
 *
 * @property User $customer
 * @property User $exec
 * @property Order $order
 * @property Dialog[] $dialogs
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_date'], 'safe'],
            [['order_id', 'customer_id', 'exec_id'], 'required'],
            [['order_id', 'customer_id', 'exec_id', 'chat_status'], 'integer'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['exec_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['exec_id' => 'id']],
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
            'chat_date' => 'Время создания чата',
            'order_id' => 'По какому заказу диалог',
            'customer_id' => 'Заказчик',
            'exec_id' => 'Мастер-Исполнитель',
            'chat_status' => '0- закрыт 1- открыт',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Exec]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExec()
    {
        return $this->hasOne(User::className(), ['id' => 'exec_id']);
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
     * Gets query for [[Dialogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['chat_id' => 'id']);
    }
}
