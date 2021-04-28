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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Album models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $user_id = Yii::$app->user->identity->id;       
        $dataProvider = new ActiveDataProvider([
            'query' => Album::find()->where(['user_id'=>$user_id])->orderBy('album_name ASC'),
        ]);

        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');

        return $this->render('index', compact('dataProvider','model','identity','work_form_name'));
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

    /**
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Album();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }

        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');

        return $this->render('create', compact('model','identity','work_form_name'));

    }

    /**
     * Updates an existing Album model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // ищем альбом         
        $model = $this->findModel($id);        

        // если пришел запрос на удаление фотки:
        if (Yii::$app->request->isPjax) { // && isset($_GET['del_photo_id'])) {

            // echo "del_photo_id=".$_GET['del_photo_id']."<br>";
            // echo "id=".$_GET['id']."<br>";

            //$res=AlbumPhoto::find()->where(['id'=>$_GET['del_photo_id']])->delete();
            AlbumPhoto::deleteAll('id='.$_GET['del_photo_id']);
            //echo "res=".$res;
            $album_photoes = $this->findAlbumPhotoes($id);
            //debug( $id);
            
            return $this->render('_form', compact('model', 'album_photoes','id'));
        }   

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        // СЧИТЫВАЕМ ДАННЫЕ ЮЗЕРА ИЗ СЕССИИ
        include_once('../libs/get_session.php');
        // ищем фотографии этого альбома
        $album_photoes = $this->findAlbumPhotoes($id);
        //debug($album_photoes);

        return $this->render('update', compact('dataProvider','model','album_photoes','identity','work_form_name','id'));        
    }

    /**
     * Deletes an existing Album model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($album_id)
    {
        $this->findModel($album_id)->delete();

        return $this->redirect(['index']);
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
