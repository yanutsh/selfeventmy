<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dialog".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $user_id Кто написал
 * @property string $send_time
 * @property string $message
 * @property int $new 1-новое сообщение
 *
 * @property Chat $chat
 * @property User $user
 */
class Dialog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dialog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'user_id', 'message'], 'required'],
            [['chat_id', 'user_id', 'new'], 'integer'],
            [['send_time'], 'safe'],
            [['message'], 'string'],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chat_id' => 'id']],
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
            'chat_id' => 'Chat ID',
            'user_id' => 'Кто написал',
            'send_time' => 'Send Time',
            'message' => 'Message',
            'new' => '1-новое сообщение',
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['id' => 'chat_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()  // кто написал сообщение
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
