<?

namespace app\components\city;

use yii\base\Widget;
use yii\helpers\Html;
use app\components\city\assets\CityAsset;
use yii\jui\AutoComplete;// Указываете путь до библиотеки

class CityWidget extends Widget
{

    public $ip;
    public $cityName;

    public function init()
    {
        parent::init();
        // берем город из кукис  
        $cookies        = \Yii::$app->request->cookies;
        $this->cityName = $cookies->getValue('city', '');        
           //debug($this->cityName,0);
        if(!$this->cityName)    // если города нет в кукис
        {
            // города нет в кукис - определяем город по умолчанию
            $fCity          = \app\models\AllowedCity::find()->where(['default_flag' => 1])->one();
            $this->cityName = $fCity->city_name;            
            
            // пытаемся определить город по геолокации 
            $this->ip = \Yii::$app->request->userIP; 
            //debug($this->ip);
            $location       = \Yii::$app->ipgeobase->getLocation($this->ip);
            if($location)  // есть геолокация
            {
                $fCity = \app\models\AllowedCity::find()->where(['city_name' => isset($location['city']) ? $location['city'] : ''])->one();
                if($fCity)
                {
                    $this->cityName = $fCity->city_name;
                }
            }

            // записываем город в кукис
            $cookies = \Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name'   => 'city',
                'value'  => $this->cityName,
                'expire' => time() + 60 * 60 * 24 * 30,
            ]));
        }       
        
    }

    public function run()
    {
        // html код перенесен в вид view/city.php
        
        $cityName = $this->cityName;
        return $this->render('city', compact('cityName'));
    }


}
