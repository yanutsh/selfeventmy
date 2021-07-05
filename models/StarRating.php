<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "star_rating".
 *
 * @property int $id
 * @property int $rating_id
 * @property float $rating_avg
 * @property int $total_votes
 *
 * @property User $rating
 */
class StarRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'star_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating_id', 'rating_avg', 'total_votes'], 'required'],
            [['rating_id', 'total_votes'], 'integer'],
            [['rating_avg'], 'number'],
            [['rating_id'], 'unique'],
            [['rating_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['rating_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rating_id' => 'Rating ID',
            'rating_avg' => 'Rating Avg',
            'total_votes' => 'Total Votes',
        ];
    }

    /**
     * Gets query for [[Rating]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRating()
    {
        return $this->hasOne(User::className(), ['id' => 'rating_id']);
    }
}
