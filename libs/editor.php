<?php
function editor(){

	session_start();
	
	//echo ('$_SESSION -tmp_avatar='.$_SESSION['tmp_avatar'])
	
	//$filename = "C://ospanel/domains/selfeventmy.loc/web/uploads/images/users/".$_SESSION['tmp_avatar']; 
	$filename = $_SERVER['DOCUMENT_ROOT'].'/web/uploads/images/users/'.$_SESSION['tmp_avatar']; 
	
	$imageFormatArray = explode('.', $filename);
	$newImageFormat= $imageFormatArray[array_key_last($imageFormatArray)];
	//return "newImageFormat=".$newImageFormat;

	if(isset($_GET['width'])
	&& isset($_GET['height'])
	&& isset($_GET['left'])
	&& isset($_GET['top'])
	&& isset($_GET['cw'])
	&& isset($_GET['ch']))
	{


		$width = $_GET['width'];
		$height = $_GET['height'];
		$top = $_GET['top'];
		$left = $_GET['left'];
		$cw = $_GET['cw'];
		$ch = $_GET['ch'];

		//return 'photo_name='.$_GET['photo_name'];

		if (isset($_GET['photo_name']) && !empty($_GET['photo_name'])) {
			$old_filename = $_GET['photo_name']; // старое имя аватаhrb		
			$imageFormatArray = explode('.', $old_filename);
			$oldImageFormat= $imageFormatArray[array_key_last($imageFormatArray)];
		} else $old_filename="";

		//Загрузка исходного изображения в зависимости от расширения :
		if ($newImageFormat=="jpg"  ||  $newImageFormat=="JPG"  ||  $newImageFormat=="jpeg"){
			//return "filename=".$filename;
	 		$source = imagecreatefromjpeg($filename);
			//return "imagecreatefromjpeg - OK";	
		}
		elseif ($newImageFormat=="png") {
			$source = imagecreatefrompng($filename);
		}
		else {return "Формат изображения не поддерживается";
	    }
		
		//return 'filename='.$filename;
		// поворачиваем при необходимости
		// # rotate
		$angles= array(8 => 90, 3 => 180, 6 => -90);
	    if(!empty($_GET['orient']) && isset($angles[$_GET['orient']])) {   
	    	// Есть данные ориентации ПОВОРАЧИВАЕМ";
	        $source = imagerotate($source, $angles[$_GET['orient']], 0);
	        $oldWidth=imagesx($source);    // ширина после поворота
	        $oldHeight=imagesy($source);   // высота после поворота	
	    } else {
	    	//Получаем размеры старого изображения если не надо поворачивать
			list($oldWidth, $oldHeight) = getimagesize($filename);	
	    }
	   //  # rotate

		//Вычисляем новые размеры и позицию фрагмента
		//Для этого нужно сначала разделить значение, например, ширину, на ширину холста - так мы получим новую ширину в процентах
		//Затем этот процент нужно умножить на ширину оригинальной фотографии - так мы получим новое значение
		$newWidth = ($width / $cw) * $oldWidth;
		$newHeight = ($height / $ch) * $oldHeight;
		$newLeft = ($left / $cw) * $oldWidth;
		$newTop = ($top / $ch) * $oldHeight;

		//Создаём изображение с новыми размерами
		$output = imagecreatetruecolor($newWidth, $newHeight); 	

		imagecopyresized($output, $source, 0, 0, $newLeft, $newTop, $newWidth, $newHeight, $newWidth, $newHeight);

		//=================================================================
				
		$new_filename = date('YmdHis').rand(100,1000).".".$newImageFormat;
	    
		$_SESSION['avatar'] =  $new_filename;	
		
	    // записываем обрезанный и повернутый файл 
		$result1 = imagejpeg($output, "C://ospanel/domains/selfeventmy.loc/web/uploads/images/users/".$new_filename);
		// записываем и во временный файл для отображения
		$result = imagejpeg($output, "C://ospanel/domains/selfeventmy.loc/web/uploads/images/users/".$newImageFormat);

		if($result)
		{
			return $new_filename; // возвращаем новое имя Аватара
		}
		else
		{
			return "string"; "fail";
		}
	}
	else
	{
		return "Error!";
	}

}