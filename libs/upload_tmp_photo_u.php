<?php
// требуется переменная - $files === $_FILES
// требуется переменная - $path_to_load - путь для загрузки

//session_start();

//print_r($files[0]);
unset($_SESSION['image_file']);
if(isset($files[0]['name']) && !empty($files[0]['name'])) 
{
  //echo "Есть файл="; //die;
      
  //Переданный массив сохраняем в переменной
  foreach($files as $f) {
    //debug($f);
    $image = $f;  
    
    // Достаем формат изображения
    $imageFormat = explode('.', $image['name']); 
    $imageFormat = $imageFormat[1];
   
    // Генерируем новое имя для изображения. Можно сохранить и со старым
    // но это не рекомендуется делать    
    
    $filename=date('YmdHis').rand(100,1000) . '.' . $imageFormat;    
    
    $imageFullName = $_SERVER['DOCUMENT_ROOT'].$path_to_load.$filename;
    //debug($imageFullName);

    // Сохраняем тип изображения в переменную
    $imageType = $image['type'];
    $imageSize = $image['size'];
    //echo "imageType=".$imageType;
    // Сверяем доступные форматы изображений, если изображение соответствует,
    // копируем изображение в папку images
    if (($imageType == 'image/gif' || $imageType == 'image/jpeg' || $imageType == 'image/png') && ($imageSize != 0 && $imageSize<=4512000)) 
    {
      //echo "tmp_name=".$image['tmp_name']." imageFullName=".$imageFullName;
      // Здесь идет процесс загрузки изображения 
      if (move_uploaded_file($image['tmp_name'],$imageFullName)) 
      {
        
        // Определяем ориентацию фотки
          // $img_meta_data= exif_read_data("../img/users/tmp_photo.jpg");
        if (!$imageType=='image/png') {
          $img_meta_data= exif_read_data($imageFullName);
          $orient= $img_meta_data['Orientation'];  
               
        }  

        //Здесь идет процесс загрузки изображения 
        $size = getimagesize($imageFullName); 
        //print_r($size);
        // с помощью этой функции мы можем получить размер пикселей изображения 
       
        if ($size[0] < 5000 && $size[1] < 5000) 
        { 
          $_SESSION['image_file'][]=$filename; // запомнили имя файла для записи в БД
          //echo $orient;  // возвращаем ориентацию фотографии
          //echo " Формат: ".$imageFormat;        

        } else 
        {
          unlink($imageFullName ); 
          // удаление файла
          echo "Не загружено! Изображение превышает допустимые размеры 5000x5000 px.";  //$size[0]."and".$size[1];              
        } 
      }else {      
          //$_SESSION["error_messages"] = "<h4 class='error_messages'>Файл не загружен, вернитеcь и попробуйте еще раз</h4>"; 
          echo "Файл не загружен, вернитеcь и попробуйте еще раз"; 
      }           
    }else  {
      //$_SESSION["error_messages"] = "<h4 class='error_messages'>Файл не загружен, Неверный формат файла или размер больше 512 кB.</h4>"; 
      if ($imageSize==0) $_SESSION['avatar']="";    
      else echo "Неверный формат файла или размер больше 4.5 МB.";  
    }
  }  
}
else  {$_SESSION['image_file']="";  echo "Нет Файла для загрузки";}
?>