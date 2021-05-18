<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_abonement".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $duration Срок действия
 * @property int $price
 * @property int|null $price_old
 * @property int $freeze_days Длительность заморозки
 * @property int $best
 *
 * @property UserAbonement[] $userAbonements
 */
class Abonement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_abonement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'duration', 'price'], 'required'],
            [['duration', 'price', 'price_old', 'freeze_days', 'best'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'duration' => 'Срок действия',
            'price' => 'Price',
            'price_old' => 'Price Old',
            'freeze_days' => 'Длительность заморозки',
            'best' => 'Best',
        ];
    }

    /**
     * Gets query for [[UserAbonements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAbonements()
    {
        return $this->hasMany(UserAbonement::className(), ['abonement_id' => 'id']);
    }
}
