<?php
 /* преобразовывает дату из формата d-m-Y в формат Y-m-d  */     
    function convert_date_en_ru($str_date){   
	    $date = date_create_from_format('Y-m-d',  $str_date);
	    $date_ru=date_format($date, 'd.m.Y');    
	    return $date_ru; 
    }