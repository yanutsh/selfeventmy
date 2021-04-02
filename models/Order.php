<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

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
 * @property int $prepayment Предоплата
 * @property string|null $added_time
 * @property int $status_order_id Статус заказа
 *
 * @property Chat[] $chats
 * @property Dialog[] $dialogs
 * @property User $user
 * @property OrderStatus $statusOrder
 * @property FsCity $city
 * @property OrderCategory[] $orderCategories
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
            [['who_need','user_id', 'city_id', 'date_from'], 'required'],
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
            'details' => 'Детали',
            'added_time' => 'Added Time',
            'who_need' => 'Кто нужен',
            'city_id' => 'Город',
            'members' => 'Число участников',
            'date_from' => 'Дата с',
            'date_to' => 'Дата до',
            'wishes' => 'Пожелания',
            'order_budget' => 'Бюджет',
            'budget_from' => 'Бюджет от',
            'budget_to' => 'Бюджет до',
            'prepayment' => 'Предоплата',
        ];
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Dialogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['order_id' => 'id']);
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

    public function getWorkForm()
    {
        return $this->hasOne(WorkForm::className(), ['id' => 'work_form_id'])->viaTable('yii_user', ['id' => 'user_id']);
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
    public function getOrderCategory()
    {
        return $this->hasMany(OrderCategory::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
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


   public function saveOrder($fields) {
        //debug($fields,0);
        $order=new Order();

        $order->user_id = Yii::$app->user->id;
        $order->who_need = Html::encode($fields['who_need']);
        $order->city_id = $fields['city_id'];
        $order->members = $fields['members'];
        $order->date_from = convert_date_ru_en($fields['date_from']);
       // $order->date_to = convert_date_ru_en($fields['date_to']);
        $order->details= Html::encode($fields['details']);
        $order->wishes = Html::encode($fields['wishes']);
        $order->budget_from = $fields['budget_from'];
        $order->budget_to = $fields['budget_to'];
        $order->order_budget = $fields['order_budget'];
        $order->prepayment = $fields['prepayment'];

        if ($order->save()) return $order->id;    //debug ("Записано");
        else return false;                        //debug(" НЕ записано");
   }

}
