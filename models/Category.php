<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_category".
 *
 * @property int $id
 * @property string $name Категория организаторов
 * @property string|null $icon
 *
 * @property ExecCategory[] $execCategories
 * @property OrderCategory[] $orderCategories
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
            [['name', 'icon'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Категория организаторов',
            'icon' => 'Иконка',
        ];
    }

    /**
     * Gets query for [[ExecCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecCategories()
    {
        return $this->hasMany(ExecCategory::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[OrderCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCategories()
    {
        return $this->hasMany(OrderCategory::className(), ['category_id' => 'id']);
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
