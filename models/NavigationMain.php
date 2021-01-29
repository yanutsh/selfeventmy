<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%navigation_main}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $link
 * @property integer $parent
 * @property integer $order
 * @property integer $blank
 * @property integer $nofollow
 */
class NavigationMain extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%navigation_main}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'parent', 'order', 'blank', 'nofollow' ], 'integer' ],
            [ [ 'title' ], 'string', 'max' => 255 ],
            [ [ 'link', 'image' ], 'string', 'max' => 1023 ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'title'    => 'Заголовок',
            'image'    => 'Изображение',
            'link'     => 'Ссылка',
            'parent'   => 'Родитель',
            'order'    => 'Порядок',
            'blank'    => 'В новом окне',
            'nofollow' => 'Noindex, Nofollow',
        ];
    }

}
