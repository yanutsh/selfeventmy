<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exec_event".
 *
 * @property int $id
 * @property int $exec_id
 * @property string $event_date
 * @property string|null $event_description
 *
 * @property User $exec
 */
class ExecEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exec_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exec_id', 'event_date'], 'required'],
            [['exec_id'], 'integer'],
            [['event_date'], 'safe'],
            [['event_description'], 'string', 'max' => 255],
            [['exec_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['exec_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exec_id' => 'Exec ID',
            'event_date' => 'Event Date',
            'event_description' => 'Event Description',
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
}
