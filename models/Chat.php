<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_chat".
 *
 * @property int $id
 * @property int $order_id
 * @property int $customer_id
 * @property int $exec_id
 * @property int $chat_status 0-Закрыт 1-открыт
 * @property string $chat_date Время создания чата
 * @property int|null $price
 * @property int|null $prepayment_summ
 * @property string|null $safe_deal on-безопасная сделка
 * @property int $isaccepted 1-принят к исполнению
 * @property int|null $result 1-успешно завершил 0-получил отказ заказчика
 * @property int $exec_cancel 1- отказ Исполнителя
 * @property string|null $accepted_time Время принятия заказа к исполнению
 * @property string|null $result_time Время записи результата
 
 * @property string|null $cancel_time Время отказа исполнителя
 *
 * @property Dialog[] $dialogs
 * @property User $exec
 * @property Order $order
 * @property User $customer
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'customer_id', 'exec_id'], 'required'],
            [['order_id', 'customer_id', 'exec_id', 'chat_status', 'price', 'prepayment_summ', 'isaccepted', 'result', 'exec_cancel','ischoose','exec_done'], 'integer'],
            [['chat_date', 'accepted_time', 'result_time', 'cancel_time','ischoose_time','exec_done_time'], 'safe'],
            [['safe_deal'], 'string', 'max' => 2],
            [['order_id', 'exec_id'], 'unique', 'targetAttribute' => ['order_id', 'exec_id']],
            [['exec_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['exec_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'customer_id' => 'Customer ID',
            'exec_id' => 'Exec ID',
            'chat_status' => '0-Закрыт 1-открыт',
            'chat_date' => 'Время создания чата',
            'price' => 'Price',
            'prepayment_summ' => 'Prepayment Summ',
            'safe_deal' => 'on-безопасная сделка',
            'isaccepted' => '1-принят к исполнению',
            'result' => '1-успешно завершил 0-получил отказ заказчика',
            'exec_cancel' => '1- отказ Исполнителя',
            'accepted_time' => 'Время принятия заказа к исполнению',
            'result_time' => 'Время записи результата
',
            'cancel_time' => 'Время отказа исполнителя',
        ];
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }
}
