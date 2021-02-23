<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_category".
 *
 * @property int $id
 * @property string $name Категория организаторов
 *
 * @property Subcategory[] $subcategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Категория услуги',
        ];
    }

    /**
     * Gets query for [[Subcategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubcategories()
    {
        return $this->hasMany(Subcategory::className(), ['category_id' => 'id']);
    }
}
