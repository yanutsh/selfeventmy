<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\User;
use app\modules\admin\models\Category;

class MainController extends AppAdminController
{    
	public function actionIndex()
	    {

	    	$num_users=User::find()->count();
	    	$num_users_auth=User::find()->where('status=10')->count();
	    	$num_categories=Category::find()->count();

	        return $this->render('index', compact('num_users', 'num_users_auth', 'num_categories'));
	    }

	public function actionTest()
    {
        return $this->render('test');
    }

}
