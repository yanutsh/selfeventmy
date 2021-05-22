<?php
// контроллер управления документами исполнителя
namespace app\controllers;

use Yii;
use app\models\UserDoc;

class DocController extends AppController
{
	public $layout = 'cabinet';

	public function actionIndex()
    {   
        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');
        $user_id = $identity['id']; 

        echo "Контроллер Документов пользователя id -". $user_id;        

    } 

    //********* Обновление   документов Исполнителя - фотографий ********************/
    public function actionUpdate($del_photo_id=null, $photo_name=null)
    {   
        // photo_name - имя файла для удаления с диска
        // del_photo_id - id фото         

        include_once('../libs/get_session.php');
        $user_id = $identity['id'];

        // если пришел запрос Pjax
        if (Yii::$app->request->isPjax) { // && isset($_GET['del_photo_id'])) {
            
            $data = Yii::$app->request->post();
            //debug($data);
            
            // если запрос на удаление фотки
            if(!is_null($del_photo_id)) { 
            	//echo "del_photo_id=".$del_photo_id;
                //debug('http:selfeventmy.loc/web/uploads/images/docs/'.$photo_name); //'/web/uploads/images/docs/'.$photo_name);
            	// удалить фото с серврера 
            	if (file_exists($_SERVER["DOCUMENT_ROOT"].'/web/uploads/images/docs/'.$photo_name)) { 	
            		unlink($_SERVER["DOCUMENT_ROOT"].'/web/uploads/images/docs/'.$photo_name);
            	}
            	else debug("Файла на диске нет");

            	// удалить фото bp БД	             
                UserDoc::deleteAll('id='.$del_photo_id);
                goto render_docs;
            }
        
            // если есть фотки для добавления
            if(!empty($_FILES['DocPhoto']['name'][0])) {   
                // сортировка файловых данных к удобному формату
                $files = sort_files($_FILES['DocPhoto']);                      
                //var_dump($_FILES['DocPhoto']['name']);      
                
                // залить файлы на сервер
                $path_to_load = '/web/uploads/images/docs/'; 
                require_once('../libs/upload_tmp_photo_u.php');

                // записать наименования файлов в БД
                foreach($files as $k=>$f) {
                    $doc_photoes = new UserDoc();
                    $doc_photoes->user_id = $user_id;
                    $doc_photoes->photo =  $_SESSION['image_file'][$k];
                    $doc_photoes->save();
                }
                
                // обновляем список фоток
                //$doc_photoes = UserDoc::find()->where(['album_id'=>$id])->
                //                    asArray()->all();

                goto render_docs;
            }    
            //debug($id);
            return $this->redirect(['cabinet/user-tuning']);
        } 
        
render_docs:
        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        //include_once('../libs/get_session.php');

        // ищем фотографии документов этого юзера        
        //$user_id = $identity['id']; 
        $doc_photoes = UserDoc::find()->where(['user_id'=>$user_id])->asArray()->all(); 

        return $this->render('update', compact('doc_photoes','identity'));     
    }     
}
