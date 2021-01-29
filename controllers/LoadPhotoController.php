<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use app\models\CategoryPhoto;
use yii\web\UploadedFile;

class LoadPhotoController extends Controller
{

	public function actionIndex()
	{
		echo "ТЕСТ Загрузка  фоток";
	}
	//=============================================

    public function actionUpload()
    {
        $model = new UploadForm();
        
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully

                // записать путь в БД=========================== 
                //debug( $model->imageFiles,0);
                $files = $model->imageFiles;

                foreach ($files as $file) {
                	//debug($file->name,0);
                	$categoryphoto = new CategoryPhoto();
                	$categoryphoto->photo = $file->name;
                	$categoryphoto->category_id = '2';     // передавать ==============================
                	$categoryphoto->save();
                }

                return 'files is uploaded successfully';
            }
        }

        return $this->render('uploadimg', ['model' => $model]);
    }

    public function actionUploadimg()
    {
		echo "Загрузка завершена";
	}

}	
 
