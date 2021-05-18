<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_user_abonement".
 *
 * @property int $id
 * @property int $user_id
 * @property int $abonement_id
 * @property string $abonement_status
 * @property string $start_date Дата оплаты
 * @property string|null $freeze_date Дата заморозки
 * @property string $end_date Дата окончания
 *
 * @property User $user
 * @property Abonement $abonement
 */
class UserAbonement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_user_abonement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'abonement_id', 'end_date'], 'required'],
            [['user_id', 'abonement_id'], 'integer'],
            [['abonement_status'], 'string'],
            [['start_date', 'freeze_date', 'end_date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['abonement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abonement::className(), 'targetAttribute' => ['abonement_id' => 'id']],
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
            'abonement_id' => 'Abonement ID',
            'abonement_status' => 'Abonement Status',
            'start_date' => 'Дата оплаты',
            'freeze_date' => 'Дата заморозки',
            'end_date' => 'Дата окончания',
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
     * Gets query for [[Abonement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbonement()
    {
        return $this->hasOne(Abonement::className(), ['id' => 'abonement_id']);
    }
}
