<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_chat".
 *
 * @property int $id
 * @property int $order_id
 * @property int $exec_id
 * @property bool $chat_status Закрыт -0, Открыт -1
 * @property string $create_time
 *
 * @property User $exec
 * @property Order $order
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
            [['order_id', 'exec_id'], 'required'],
            [['order_id', 'exec_id'], 'integer'],
            [['chat_status'], 'boolean'],
            [['create_time'], 'safe'],
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
            'order_id' => 'Order ID',
            'exec_id' => 'Exec ID',
            'chat_status' => 'Закрыт -0, Открыт -1',
            'create_time' => 'Create Time',
        ];
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
}
