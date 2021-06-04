<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id_chat
 * @property string $chat_date Время создания чата
 * @property int $id_order По какому заказу диалог
 * @property int $id_customer Заказчик
 * @property int $id_master Мастер-Исполнитель
 *
 * @property User $customer
 * @property User $master
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
            [['id_order', 'id_customer', 'id_master'], 'required'],
            [['id_order', 'id_customer', 'id_master'], 'integer'],
            [['id_customer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_customer' => 'id']],
            [['id_master'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_master' => 'id']],
            [['id_order'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['id_order' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_chat' => 'Id Chat',
            'chat_date' => 'Время создания чата',
            'id_order' => 'По какому заказу диалог',
            'id_customer' => 'Заказчик',
            'id_master' => 'Мастер-Исполнитель',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'id_customer']);
    }

    /**
     * Gets query for [[Master]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(User::className(), ['id' => 'id_master']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'id_order']);
    }

    /**
     * Gets query for [[Dialogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['id_chat' => 'id_chat']);
    }
}
