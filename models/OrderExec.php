<?php // модель таблицы Исполнителей заказов (по итогам переговоров)

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_order_exec".
 *
 * @property int $id
 * @property int $order_id
 * @property int $exec_id
 * @property int $price
 * @property int $prepayment_summ
 * @property int $safe_deal 1-безопасная сделка
 * @property int|null $result 1-успешно завершил 0-получил отказ
 * @property int exec_cancel  1-отказ исполнителя
 *
 * @property User $exec
 * @property Order $order
 */
class OrderExec extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_order_exec';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'exec_id', 'price'], 'required'],
            [['order_id', 'exec_id', 'price', 'prepayment_summ', 'result','exec_cancel'], 'integer'],
            ['safe_deal', 'safe'],
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
            'price' => 'Стоимость *',
            'prepayment_summ' => 'Предоплата (если надо)',
            'safe_deal' => 'Безопасная сделка',
            'result' => '1-успешно завершил 0-получил отказ',
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
