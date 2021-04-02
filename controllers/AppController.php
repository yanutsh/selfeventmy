<?php

namespace app\Controllers;

class AppController extends \yii\web\Controller
{
    
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
            
        ];
    }
    
}
