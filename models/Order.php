<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_orders".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status_order_id
 * @property string $details
 * @property string|null $added_time
 * @property int $category_id
 * @property int $order_budget
 *
 * @property User $user
 * @property StatusOrder $statusOrder
 * @property Category $category
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'details'], 'required'],
            [['user_id', 'status_order_id', 'category_id', 'order_budget'], 'integer'],
            [['details'], 'string'],
            [['added_time'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['status_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusOrder::className(), 'targetAttribute' => ['status_order_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status_order_id' => 'Status Order ID',
            'details' => 'Details',
            'added_time' => 'Added Time',
            'category_id' => 'Category ID',
            'order_budget' => 'Order Budget',
        ];
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

    /**
     * Gets query for [[StatusOrder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusOrder()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'status_order_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
