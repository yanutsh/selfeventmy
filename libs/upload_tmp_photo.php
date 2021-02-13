<?php
session_start();
//print_r($_FILES[0]);
if(isset($_FILES[0]['name']) && !empty($_FILES[0]['name'])) 
{
   //echo "Есть файл="; //die;
      
  //Переданный массив сохраняем в переменной
  $image = $_FILES[0];  
  
  // Достаем формат изображения
  $imageFormat = explode('.', $image['name']); 
  $imageFormat = $imageFormat[1];
 
  // Генерируем новое имя для изображения. Можно сохранить и со старым
  // но это не рекомендуется делать
  // 

  $filename='tmp_photo'. '.' . $imageFormat;
  //echo "filename=". $filename;
  // $filename='tmp_photo_' .$_GET['add_tmp']. '.' . $imageFormat;
  $imageFullName = $_SERVER['DOCUMENT_ROOT'].'/web/uploads/images/users/' . $filename;
 
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
        $_SESSION['tmp_avatar']=$filename; // запомнили имя файла для записи в БД
        echo $orient;  // возвращаем ориентацию фотографии
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
  }else 
  {
    //$_SESSION["error_messages"] = "<h4 class='error_messages'>Файл не загружен, Неверный формат файла или размер больше 512 кB.</h4>"; 
    if ($imageSize==0) $_SESSION['avatar']="";    
    else echo "Неверный формат файла или размер больше 4.5 МB.";  
  }
}

else  {$_SESSION['avatar']="";  echo "Нет Файла для загрузки";}

?>