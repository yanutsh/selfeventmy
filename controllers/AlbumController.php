<?php

namespace app\controllers;

use Yii;
use app\models\Album;
use app\models\AlbumPhoto;
use app\models\WorkForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends AppController
{
    public $layout = 'cabinet';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    //********* Список всех альбомов **************************************************/
    public function actionIndex()
    {   
        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');

        //$identity = Yii::$app->user->identity; 
        //$user_id = Yii::$app->user->identity->id; 
        $user_id = $identity['id'];        
                
        $dataProvider = new ActiveDataProvider([
            'query' => Album::find()->where(['user_id'=>$user_id])->orderBy('album_name ASC'),
        ]);        

        return $this->render('index', compact('dataProvider','identity','work_form_name'));
    }

    /**
     * Displays a single Album model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    //********* Создание нового альбома *********************************************/
    public function actionCreate()
    {
        $model = new Album();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            $user_id = $identity['id']; 
            $dataProvider = new ActiveDataProvider([
            'query' => Album::find()->where(['user_id'=>$user_id])->orderBy('album_name ASC'),
        ]); 

            return $this->render('index', compact('dataProvider','model','identity','work_form_name'));
        }

        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');

        $model->user_id = $identity->id;
        $model->save(); // получаем id нового альбома

        return $this->render('update', compact('model','identity','work_form_name'));

    }

    //********* Обновление  альбома - добавление фотографий *************************/
    public function actionUpdate($id=null,$del_photo_id=null)
    {   
        // id - id альбома
        // del_photo_id - id фото
        $user_id = $identity['id']; 
        $dataProvider = new ActiveDataProvider([
            'query' => Album::find()->where(['user_id'=>$user_id])->orderBy('album_name ASC'),
            ]); 
        
        // ищем альбом         
        $model = $this->findModel($id);
        // echo "model-1";
        // debug($model,0);

        // if ($model->load(Yii::$app->request->post())) {
        //     //debug("POST - загрузка");          
        //     $model->save();     // сохраняем имя альбома  
        //     return $this->render('index', compact('dataProvider','model','identity','work_form_name','id'));  
        // }
             
        // если пришел запрос Pjax
        if (Yii::$app->request->isPjax) { // && isset($_GET['del_photo_id'])) {
            //$model = new Album();
            $data = Yii::$app->request->post();
            //debug($data);
            if ( $data) {
               
                $model->album_name = $data['Album']['album_name'];         
                $model->save();     // сохраняем имя альбома  
                //echo "model-2";
                //debug($model);
                }//else debug ("Не save");
            // если запрос на удаление фотки
            if(!is_null($del_photo_id)) { 
                
                AlbumPhoto::deleteAll('id='.$del_photo_id);

                // обновляем список фоток
                $album_photoes = $this->findAlbumPhotoes($id); 
                
                return $this->render('_form', compact('model', 'album_photoes','id'));
            }
        
            // если есть фотки для добавления
            if(!empty($_FILES['AlbumPhoto']['name'][0])) {   
                //echo "Альбом id=".$id."<br><br>";                 
                //debug(sort_files($_FILES['AlbumPhoto']));

                $files = sort_files($_FILES['AlbumPhoto']);                      
                //var_dump($_FILES['AlbumPhoto']['name']);      
                
                // залить файлы на сервер
                $path_to_load = '/web/uploads/images/portfolio/'; 
                require_once('../libs/upload_tmp_photo_u.php');

                // записать наименования файлов в БД
                foreach($files as $k=>$f) {
                    $album_photoes = new AlbumPhoto();
                    $album_photoes->album_id = $id;
                    $album_photoes->photo_name =  $_SESSION['image_file'][$k];
                    $album_photoes->save();
                }
                
                // обновляем список фоток
                $album_photoes = AlbumPhoto::find()->where(['album_id'=>$id])->
                                    asArray()->all();

                return $this->render('update', compact('model','identity','work_form_name','album_photoes')); 
            }    
            //debug($id);
            return $this->redirect(['index']);
        }   

        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');
        // ищем фотографии этого альбома
        $album_photoes = $this->findAlbumPhotoes($id);

        return $this->render('update', compact('model','album_photoes','identity','work_form_name','id'));                            
    }

    /**
     * Deletes an existing Album model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // $id - id альбома
    {
        //debug("Удаляем альбом");
        $this->findModel($id)->delete();
       
        include_once('../libs/get_session.php');
        $user_id = $identity['id']; 
        $dataProvider = new ActiveDataProvider([
            'query' => Album::find()->where(['user_id'=>$user_id])->orderBy('album_name ASC'),
            ]); 
        return $this->render('index', compact('dataProvider','identity','work_form_name','id')); 
    }

    /**
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($album_id)
    {
        if (($model = Album::findOne($album_id)) !== null) {
            //debug($model);
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // Поиск фотографий альбома с заданным $id
    protected function findAlbumPhotoes($album_id)
    {
        if ( ($album_photoes = AlbumPhoto::find()->where(['album_id'=>$album_id])->asArray()->all()) !== null) {
            return $album_photoes;
        }

        throw new NotFoundHttpException('The album_photoes does not exist.');
    }
}
