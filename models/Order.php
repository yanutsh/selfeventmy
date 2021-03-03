<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status_order_id Статус заказа
 * @property string $details
 * @property string|null $added_time
 * @property string|null $who_need Кто нужен
 * @property int $city_id
 * @property int $members Число участников
 * @property string $date_from
 * @property string|null $date_to
 * @property string|null $wishes Пожелания
 * @property int $order_budget
 * @property int|null $budget_from
 * @property int|null $budget_to
 * @property int|null $prepayment Предоплата
 *
 * @property User $user
 * @property OrderStatus $statusOrder
 * @property OrderCategory[] $orderCategories
 * @property Category[] $categories
 * @property OrderPhoto[] $orderPhotos
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'details', 'city_id', 'members', 'date_from'], 'required'],
            [['user_id', 'status_order_id', 'city_id', 'members', 'order_budget', 'budget_from', 'budget_to', 'prepayment'], 'integer'],
            [['details', 'wishes'], 'string'],
            [['added_time', 'date_from', 'date_to'], 'safe'],
            [['who_need'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['status_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['status_order_id' => 'id']],
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
            'status_order_id' => 'Статус заказа',
            'details' => 'Details',
            'added_time' => 'Added Time',
            'who_need' => 'Кто нужен',
            'city_id' => 'City ID',
            'members' => 'Число участников',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'wishes' => 'Пожелания',
            'order_budget' => 'Order Budget',
            'budget_from' => 'Budget From',
            'budget_to' => 'Budget To',
            'prepayment' => 'Предоплата',
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
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status_order_id']);
    }

    /**
     * Gets query for [[OrderCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCategories()
    {
        return $this->hasMany(OrderCategory::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('yii_order_category', ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderPhotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPhotos()
    {
        return $this->hasMany(OrderPhoto::className(), ['order_id' => 'id']);
    }

    public function getOrderCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
