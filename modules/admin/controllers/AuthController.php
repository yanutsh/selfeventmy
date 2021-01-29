<?php

namespace app\modules\admin\controllers;
use Yii;
use app\models\LoginForm;

class AuthController extends AppAdminController
{    
	public $layout = 'auth';
	
	public function actionLogin()
    {
        
        if( !Yii::$app->user->isGuest )
        {
        	return $this->redirect('/admin');
            //return $this->goHome();
        }

        $model = new LoginForm();
        if( $model->load(Yii::$app->request->post()) && $model->login() )
        {
           return $this->redirect('/admin');
            // return $this->goBack();

        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
		return $this->redirect('/admin');
        //return $this->goHome();
    }

}
