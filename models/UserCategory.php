<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_user_category".
 *
 * @property int $user_id
 * @property int $category_id
 * @property int $subcategory_id
 * @property int $price
 * @property int $price_from
 * @property int $price_to
 *
 * @property Category $category
 * @property Subcategory $subcategory
 * @property User $user
 */
class UserCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_user_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'subcategory_id', 'price_from'], 'required'],
            [['user_id', 'category_id', 'subcategory_id', 'price', 'price_from', 'price_to'], 'integer'],
            [['user_id', 'category_id', 'subcategory_id'], 'unique', 'targetAttribute' => ['user_id', 'category_id', 'subcategory_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['subcategory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subcategory::className(), 'targetAttribute' => ['subcategory_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['price', 'price_from', 'price_to'], 'default', 'value'=>0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'subcategory_id' => 'Subcategory ID',
            'price' => 'Стоимость услуги',
            'price_from' => 'Стоимость услуги от',
            'price_to' => 'Стоимость услуги до',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Subcategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubcategory()
    {
        return $this->hasOne(Subcategory::className(), ['id' => 'subcategory_id']);
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
}
