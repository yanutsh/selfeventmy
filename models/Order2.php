<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_order".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $who_need Кто нужен
 * @property string $details
 * @property int $city_id
 * @property int $members Число участников
 * @property string $date_from
 * @property string|null $date_to
 * @property string|null $wishes Пожелания
 * @property int|null $order_budget
 * @property int|null $budget_from
 * @property int|null $budget_to
 * @property int|null $prepayment Предоплата
 * @property string|null $added_time
 * @property int $status_order_id Статус заказа
 *
 * @property User $user
 * @property OrderStatus $statusOrder
 * @property FsCity $city
 * @property OrderCategory[] $orderCategories
 * @property OrderPhoto[] $orderPhotos
 */
class Order2 extends \yii\db\ActiveRecord
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
            [['user_id', 'details', 'members', 'date_from'], 'required'],
            [['user_id', 'city_id', 'members', 'order_budget', 'budget_from', 'budget_to', 'prepayment', 'status_order_id'], 'integer'],
            [['details', 'wishes'], 'string'],
            [['date_from', 'date_to', 'added_time'], 'safe'],
            [['who_need'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['status_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['status_order_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => FsCity::className(), 'targetAttribute' => ['city_id' => 'id']],
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
            'who_need' => 'Кто нужен',
            'details' => 'Details',
            'city_id' => 'City ID',
            'members' => 'Число участников',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'wishes' => 'Пожелания',
            'order_budget' => 'Order Budget',
            'budget_from' => 'Budget From',
            'budget_to' => 'Budget To',
            'prepayment' => 'Предоплата',
            'added_time' => 'Added Time',
            'status_order_id' => 'Статус заказа',
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
        return $this->hasOne(OrderStatus::className(), ['id' => 'status_order_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(FsCity::className(), ['id' => 'city_id']);
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
     * Gets query for [[OrderPhotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPhotos()
    {
        return $this->hasMany(OrderPhoto::className(), ['order_id' => 'id']);
    }
}
