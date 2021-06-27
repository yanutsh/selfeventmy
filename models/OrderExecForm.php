<?php // форма ввода данных Заказчиком (по итогам переговоров с Исполнителем)

namespace app\models;

use Yii;
use yii\base\Model;

class OrderExecForm extends Model
{
    public $price;
    public $prepayment_summ;
    public $safe_deal;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['price', 'required'],
            [['price', 'prepayment_summ'], 'integer'],
            ['safe_deal', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [           
            'price' => 'Стоимость *',
            'prepayment_summ' => 'Предоплата (если надо)',
            'safe_deal' => 'Безопасная сделка',           
        ];
    }

}
