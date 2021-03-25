<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_review".
 *
 * @property int $id
 * @property int $from_user_id
 * @property int $for_user_id
 * @property string $review
 *
 * @property User $fromUser
 * @property User $forUser
 */

// Модель Отзывов об Исполнителях и Заказчиках
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_user_id', 'for_user_id', 'review'], 'required'],
            [['from_user_id', 'for_user_id'], 'integer'],
            [['review'], 'string'],
            [['from_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from_user_id' => 'id']],
            [['for_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['for_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_user_id' => 'From User ID',
            'for_user_id' => 'For User ID',
            'review' => 'Review',
        ];
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
     * Gets query for [[ForUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForUser()
    {
        return $this->hasOne(User::className(), ['id' => 'for_user_id']);
    }
}
