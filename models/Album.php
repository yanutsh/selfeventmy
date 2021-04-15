<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_album".
 *
 * @property int $id
 * @property int $user_id
 * @property string $album_name
 *
 * @property User $user
 * @property AlbumPhoto[] $albumPhotos
 */
class Album extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_album';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'album_name'], 'required'],
            [['user_id'], 'integer'],
            [['album_name'], 'string', 'max' => 255],
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
            'album_name' => 'Название альбома',
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
     * Gets query for [[AlbumPhotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbumPhotos()
    {
        return $this->hasMany(AlbumPhoto::className(), ['album_id' => 'id']);
    }
}
