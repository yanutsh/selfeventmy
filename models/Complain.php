<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_complain".
 *
 * @property int $from_user_id
 * @property int $for_user_id
 * @property int $order_id
 * @property string $complain жалоба
 */
class Complain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_complain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_user_id', 'for_user_id', 'order_id', 'complain'], 'required'],
            [['from_user_id', 'for_user_id', 'order_id'], 'integer'],
            [['complain'], 'string'],
            [['from_user_id', 'for_user_id', 'order_id'], 'unique', 'targetAttribute' => ['from_user_id', 'for_user_id', 'order_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'from_user_id' => 'From User ID',
            'for_user_id' => 'For User ID',
            'order_id' => 'Order ID',
            'complain' => 'Опишите жалобу',
        ];
    }
}
