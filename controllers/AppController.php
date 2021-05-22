<?php
namespace app\Controllers;

use Yii;

class AppController extends \yii\web\Controller
{


    // public function __construct($id, $modul, $config){       
        
    //     parent::__construct($id, $modul, $config); 
    // }

    public function beforeAction($action)
    {
        // считываем данные юзера из сессии
        // $session = Yii::$app->session;
        // $identity = $session['identity'];
        // $work_form_name = $session['work_form_name'];
        return parent::beforeAction($action);
    }
    
	/**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [ 
        	// получение списка новых сообщений текущего юзера
        	'get-new-mess' => [
        		'class' => 'app\controllers\actions\GetNewMessAction',              
        	],
            'get-data-from-cache' => [
                'class' => 'app\controllers\actions\GetDataFromCacheAction',    
            ]          
            
        ];
    }
    
}
