<?php
/* преобразовывает дату из формата d-m-Y в формат Y-m-d  */ 
    function convert_date_ru_en($str_date){     
	    $date = date_create_from_format('d.m.Y',  $str_date);
	    $date_en = date_format($date, 'Y-m-d');  
	    return $date_en; 
    }
    ?>