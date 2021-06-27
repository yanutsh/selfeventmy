<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_complain".
 *
 * @property int $from_user_id
 * @property int $for_user_id
 * @property int $order_id
 * @property string $complain жалоба
 *
 * @property User $forUser
 * @property User $fromUser
 * @property Order $order
 */
class Complain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_complain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_user_id', 'for_user_id', 'order_id', 'complain'], 'required'],
            [['from_user_id', 'for_user_id', 'order_id'], 'integer'],
            [['complain'], 'string'],
            [['from_user_id', 'for_user_id', 'order_id'], 'unique', 'targetAttribute' => ['from_user_id', 'for_user_id', 'order_id']],
            [['for_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['for_user_id' => 'id']],
            [['from_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from_user_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'from_user_id' => 'From User ID',
            'for_user_id' => 'For User ID',
            'order_id' => 'Order ID',
            'complain' => 'Опишите суть претензий',
        ];
    }

    /**
     * Gets query for [[ForUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForUser()
    {
        return $this->hasOne(User::className(), ['id' => 'for_user_id']);
    }

    /**
     * Gets query for [[FromUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
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
