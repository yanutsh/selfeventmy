<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_category_photo".
 *
 * @property int $id id фотографии
 * @property int $category_id id категории
 * @property string $photo путь к фотографии
 */
class CategoryPhoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_category_photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'photo'], 'required'],
            [['category_id'], 'string', 'max' => 8],
            [['photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id фотографии',
            'category_id' => 'id категории',
            'photo' => 'путь к фотографии',
        ];
    }
}
