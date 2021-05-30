<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_doc_list".
 *
 * @property int $id
 * @property string $doc_name
 */
class DocList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_doc_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_name'], 'required'],
            [['doc_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doc_name' => 'Doc Name',
        ];
    }
}
