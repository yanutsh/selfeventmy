<?php
 /* преобразовывает дату из формата d-m-Y в формат Y-m-d  */ 

    function convert_datetime_en_ru($str_date){ 
        
    	$date = date_create_from_format('Y-m-d H:i:s',  $str_date);
	    
	    $date_ru['dmY']=date_format($date, 'd.m.Y');  			// без времени
	    $date_ru['dmYHis']=date_format($date, 'd.m.Y H:i:s'); 	// со временем 
	    $date_ru['Hi']=date_format($date, 'H:i'); 	            // часы + минуты 

	    $timeunix = strtotime($str_date); 						// строка в timestamp				
	    $date_ru['dMruY']=rdate('d M Y', $timeunix);    		// месяц на русском
	    $date_ru['Mru']=rdate('M', $timeunix); 	 				// месяц на русском

		$days = array(
		    // 'Понедельник', 'Вторник', 'Среда',
		    // 'Четверг', 'Пятница', 'Суббота','Воскресенье'
		    'Пн', 'Вт', 'Ср','Чт', 'Пт', 'Сб','Вс'
		);

		$date_ru['w']=$days[date('w',$timeunix)];

	    return $date_ru; 
	}    
    