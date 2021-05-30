<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_visit_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $enter_time
 * @property string $update_time
 * @property int $session_id
 *
 * @property User $user
 */
class VisitLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_visit_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'session_id'], 'required'],
            [['user_id'], 'integer'],
            [['enter_time', 'update_time', 'session_id'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'enter_time' => 'Enter Time',
            'update_time' => 'Update Time',
            'session_id' => 'Session ID',
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

    public function addRecord() {
       
    }


}
