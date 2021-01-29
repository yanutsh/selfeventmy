<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%allowed_city}}".
 *
 * @property integer $id
 * @property string $region
 * @property string $region_name
 * @property string $city
 * @property string $city_name
 * @property integer $default_flag
 */
class AllowedCity extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%allowed_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'default_flag' ], 'integer' ],
            [ [ 'default_flag' ], 'default', 'value' => 0 ],
            [ [ 'region', 'city', 'region_name', 'city_name' ], 'string', 'max' => 2048 ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'region_name'  => 'Регион',
            'city_name'    => 'Город-Имя',
            'region'       => 'Регион',
            'city'         => 'Город',
            'default_flag' => 'По умолчанию',
        ];
    }

    public function beforeSave($insert)
    {
        if( parent::beforeSave($insert) )
        {
            if( $this->default_flag )
            {
                $query  = AllowedCity::find();
                if( !$insert )
                    $query->where([ 'not', [ 'id' => $this->id ] ]);
                $models = $query->all();
                foreach( $models as $m )
                {
                    $m->default_flag = 0;
                    $m->save();
                }
            }
            return true;
        }
        return false;
    }

}
