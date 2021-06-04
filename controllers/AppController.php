<?php
namespace app\Controllers;

use Yii;
use app\models\VisitLog;

class AppController extends \yii\web\Controller
{


    // public function __construct($id, $modul, $config){       
        
    //     parent::__construct($id, $modul, $config); 
    // }

    public function beforeAction($action)
    {        
        // *****************************************************************************
        // Запись посещения юзера в журнал *********************************************
        $session = Yii::$app->session;
        
        // проверяем наличие в логе пары user_id  и session_id
        $visitlog = VisitLog::find()->where(['and', ['user_id'=>Yii::$app->user->id, 'session_id'=>Yii::$app->session->id]])->one();
        
        if($visitlog) {  //"Есть запись в Логе" - пишем время обновления);
            $visitlog->update_time = date('Y-m-d H:i:s');
            $visitlog->save();
            //debug('Обновление update_time записан',0);
        }

        else {  //"НЕТ запись в Логе" - делаем новую запись);            
            $visitlog = new VisitLog;
            $visitlog->user_id = Yii::$app->user->id;
            $visitlog->session_id = Yii::$app->session->id;
            $visitlog->save();            
        }
        
        // $min_date = VisitLog::find()
        //             ->select(['min(enter_time)'])
        //             ->where(['user_id'=>Yii::$app->user->id])
        //             ->asArray()
        //             ->one();  // первый вход юзера

        // $max_date = VisitLog::find()
        //             ->select(['max(update_time)'])
        //             ->where(['user_id'=>Yii::$app->user->id])
        //             ->asArray()
        //             ->one();  // последняя активность юзера 
        
        //debug($min_date,0);
        //debug($max_date);
    
    // Запись посещения юзера в журнал Конец****************************************
    // *****************************************************************************    
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
