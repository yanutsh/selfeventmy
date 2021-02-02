<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_work_form".
 *
 * @property int $id
 * @property string $work_form_name
 */
class WorkForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_work_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'work_form_name'], 'required'],
            [['id'], 'integer'],
            [['work_form_name'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_form_name' => 'Work Form Name',
        ];
    }
}
