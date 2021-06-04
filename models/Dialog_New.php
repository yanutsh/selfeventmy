<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dialog".
 *
 * @property int $id_dialog
 * @property int $id_chat
 * @property string $time_message
 * @property string $message
 * @property int $from_who 1- сообщение от Исполнителя
 * @property int $new_message
 *
 * @property Chat $chat
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
            [['id_chat', 'message'], 'required'],
            [['id_chat', 'from_who', 'new_message'], 'integer'],
            [['time_message'], 'safe'],
            [['message'], 'string'],
            [['id_chat'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['id_chat' => 'id_chat']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dialog' => 'Id Dialog',
            'id_chat' => 'Id Chat',
            'time_message' => 'Time Message',
            'message' => 'Message',
            'from_who' => '1- сообщение от Исполнителя',
            'new_message' => 'New Message',
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['id_chat' => 'id_chat']);
    }
}
