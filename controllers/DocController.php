<?php
// контроллер управления документами исполнителя
namespace app\controllers;

use Yii;
use app\models\User;
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
        // del_photo_id - id фото
        // photo_name - имя файла для удаления с диска         

        //include_once('../libs/get_session.php');
        $identity = Yii::$app->user->identity;
        $user_id = $identity['id'];

        // если пришел запрос Pjax
        if (Yii::$app->request->isPjax) { // && isset($_GET['del_photo_id'])) {
            
            $data = Yii::$app->request->post();
            //debug($data);
            
            // если запрос на удаление фотки
            if(!is_null($del_photo_id)) { 
            	
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
            $max_doc_photos=5;
            if(!empty($_FILES['DocPhoto']['name'][0])) {  
            	//debug($data['save_docs']);
            	$kol_to_add = $max_doc_photos - $data['save_docs'];  
                // сортировка файловых данных к удобному формату
                $files = sort_files($_FILES['DocPhoto']);                      
                //var_dump($_FILES['DocPhoto']['name']);      
                
                // залить файлы на сервер
                $path_to_load = '/web/uploads/images/docs/'; 
                require_once('../libs/upload_tmp_photo_u.php');

                // записать наименования файлов в БД
                $kf =1;
                foreach($files as $k=>$f) {
                	if ($kf > $kol_to_add) break;

                    $doc_photoes = new UserDoc();
                    $doc_photoes->user_id = $user_id;
                    $doc_photoes->photo =  $_SESSION['image_file'][$k];
                    $doc_photoes->save();
                    $kf++;
                }

                // записываем юзеру в БД признак обновления документов
                $user = User::findOne($identity['id']);
                $user->isnewdocs = 1;
                $user->isconfirm = 0; // сбросили признак
                $user->save();

                // отправляем уведомление админу об обновлении docs
                $email_subject = 'Запрос на подтверждение документов';
                $text = 'Пользователем '.$identity['username'].' введены новые документы. Перейдите на сайт для их одобрения';
                send_email_to_admin($email_subject,$text);

                goto render_docs;
            }    
            
            //debug($identity['isnewdocs']);
           
            if ($identity['isnewdocs']) return $this->redirect('docreceived'); 
            else return $this->redirect(['cabinet/user-tuning']);
        } 
        
        render_docs:        
        $doc_photoes = UserDoc::find()->where(['user_id'=>$user_id])->asArray()->all(); 

        return $this->render('update', compact('doc_photoes','identity'));     
    } 

    public function actionDocreceived(){
    	return $this->render('docReceived'); 
    }    
}
