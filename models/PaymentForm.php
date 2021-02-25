<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_payment_form".
 *
 * @property int $id
 * @property string $payment_name
 */
class PaymentForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_payment_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_name'], 'required'],
            [['payment_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_name' => 'Payment Name',
        ];
    }
}
