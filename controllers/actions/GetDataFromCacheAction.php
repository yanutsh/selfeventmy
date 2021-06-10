<?php
// получение неизменяемых данных из БД в кеш
namespace app\controllers\actions;

use Yii;
use yii\base\Action;
use app\models\Abonement;
use app\models\Category;
use app\models\City;
use app\models\DocList;
use app\models\WorkForm;
use app\models\PaymentForm;

class GetDataFromCacheAction extends Action   
    {   
        public function run()   
        {   
	        $cache = \Yii::$app->cache;
	        // Обнуляем кеш - ТЕХНОЛОГИЯ ДЛЯ ОТЛАДКИ !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        	Yii::$app->cache->flush();    

	        $cache_data=array();

	        // категории услуг
	        $cache_data['category'] = $cache->getOrSet('category',function()
	             {return Category::find()->orderBy('name')->all();});

	        // города
	        $cache_data['city'] = $cache->getOrSet('city',function()
	             {return City::find()->orderBy('name')->all();});

	        // формы работы
	        $cache_data['work_form'] = $cache->getOrSet('work_form',function()
	             {return WorkForm::find()->orderBy('work_form_name')->asArray()->all();});

	        // формы оплаты
	        $cache_data['payment_form'] = $cache->getOrSet('payment_form',function()
	             {return PaymentForm::find()->orderBy('payment_name')->asArray()->all();});

	        // выбираем только абонементы без заморозки	              
	        $cache_data['abonement_nofreeze'] = $cache->getOrSet('abonement_nofreeze',function()
	             {return Abonement::find()->where(['=','freeze_days','0']) 
	                   ->orderBy('price ASC')->asArray()->all();});

	        // выбираем только абонементы с заморозкой
	        $cache_data['abonement_freeze'] = $cache->getOrSet('abonement_freeze',function()
	             {return Abonement::find()->where(['>','freeze_days','0']) 
	                   ->orderBy('price ASC')->asArray()->all();});

	        $cache_data['doc_list'] = $cache->getOrSet('doc_list',function()
	             {return DocList::find()->orderBy('doc_name ASC')->asArray()->all();});

	        // debug($cache_data['abonement_nofreeze']);
	        
	        return $cache_data; 
	                    
        }
  
    }
