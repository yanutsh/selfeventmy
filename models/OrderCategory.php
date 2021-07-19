<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_order_category".
 *
 * @property int $order_id
 * @property int $category_id
 * @property int $subcategory_id
 *
 * @property Order $order
 * @property Category $category
 */
class OrderCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_order_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'category_id', 'subcategory_id'], 'required'],
            [['order_id', 'category_id', 'subcategory_id'], 'integer'],
            [['order_id', 'category_id', 'subcategory_id'], 'unique', 'targetAttribute' => ['order_id', 'category_id', 'subcategory_id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'category_id' => 'Category ID',
            'subcategory_id' => 'Subcategory ID',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
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

    public function saveOrderCategory($fields, $order_id) {
        //debug($fields);
        //$order=new Order();
        $i=0;
        foreach ($fields['category_id'] as $cat_id) {
            //echo "<br>cat_id=".$cat_id;
            if ($cat_id >0) {

                $order_category = new OrderCategory();

                $order_category->order_id = $order_id;
                $order_category->category_id = $cat_id; 
                if(!empty($fields['subcategory_id'][$i]))       
                    $order_category->subcategory_id = $fields['subcategory_id'][$i];
                else $order_category->subcategory_id = 0;

                $order_category->save();                
            }
            $i++;            
        }
        //debug ("Конец foreach");
        //else return false;                        //debug(" НЕ записано");
   }

}
