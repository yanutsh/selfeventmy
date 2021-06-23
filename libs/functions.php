<?php 

// Ввод массива на печать в удобном виде
function debug($data,$die=true){	
	echo "<pre>";
	print_r($data);
	echo"<pre>";
	if ($die) die;
}

// Генерация кода из заданного числа цифр
function confirm_code($len=6){		
	$str="";
	for($i=0; $i<$len; $i++){
		$num=rand(0,9);
		$str .=$num;
	}
	return $str;		
}

function cht_status($chat_list) {
  if( $chat_list['exec_cancel']==1) $cht_status="Отказ Исполнителя"; 
  elseif( $chat_list['result']===0) $cht_status="Отказ заказчика"; 
  elseif( $chat_list['result']==1) $cht_status="Заказ выполнен"; 
  elseif( $chat_list['isaccepted']==1) $cht_status="Принят к исполнению";
  else    $cht_status ="Диалог открыт";
  return   $cht_status; 
}

// отправка email
function send_email($email,$email_subject,$text){	
	// отправка смс OT Aдмина на email Юзера с текстом  $text
	$email_from = \Yii::$app->params['adminEmail'];
	//$email_subject = \Yii::$app->params['email_subject'];	
	
	Yii::$app->mailer->compose()
	    ->setFrom($email_from)
	    ->setTo($email)
	    ->setSubject($email_subject)
	    ->setTextBody($text)
	    ->setHtmlBody($text)
	    ->send();	

	    Yii::$app->session->setFlash('send_code', $text. ' Письмо отправлено');
	    //return true;	 
}

function send_email_to_admin($email_subject,$text){	
	// отправка смс Aдминy от Админа с текстом  $text
	$email_from = \Yii::$app->params['adminEmail'];
	$email      = \Yii::$app->params['adminEmail'];
	$email_subject = \Yii::$app->params['email_subject'];	
	
	Yii::$app->mailer->compose()
	    ->setFrom($email_from)
	    ->setTo($email)
	    ->setSubject($email_subject)
	    ->setTextBody($text)
	    ->setHtmlBody($text)
	    ->send();	

	    Yii::$app->session->setFlash('send_code', $text. ' Письмо отправлено');
	    //return true;	 
}

// отправка смс
function send_sms($phone,$text){	
	// отправка смс на телефон $phone с текстом  $text
	$login_sms = \Yii::$app->params['login_sms'];
	$password_sms = \Yii::$app->params['password_sms'];
	$title_sms = \Yii::$app->params['title_sms'];
	$sadr_sms = \Yii::$app->params['sadr_sms'];
		
	$id_sms = file_get_contents ('http://gateway.api.sc/get/?user='.$login_sms.'&pwd='.$password_sms.'&name_deliver='.$title_sms.'&sadr='.$sadr_sms.'&dadr='.$phone.'&text='.$text);

	if ($id_sms)
        Yii::$app->session->setFlash('send_code', $text.' СМС отправлено'); 
    else 
        Yii::$app->session->setFlash('send_code', $text. ' СМС НЕ отправлено'); 

	return $id_sms; // id сообщения 
}	

// преобразовывает дату из формата Y-m-d в формат d-m-Y  */     
function convert_date_en_ru($str_date){   
    $date = date_create_from_format('Y-m-d',  $str_date);
    $date_ru=date_format($date, 'd.m.Y');    
    return $date_ru; 
}

/* преобразовывает дату из формата d-m-Y в формат Y-m-d  */ 
function convert_date_ru_en($str_date){     
    $date = date_create_from_format('d.m.Y',  $str_date);
    $date_en = date_format($date, 'Y-m-d');  
    return $date_en; 
}

/* преобразовывает дату БД из Y-m-d H:i:s в разные форматы формата   */ 
function convert_datetime_en_ru($str_date){ 
    
	$date = date_create_from_format('Y-m-d H:i:s',  $str_date);
    
    $date_ru['dmY']=date_format($date, 'd.m.Y');  			// без времени
    $date_ru['dmYHis']=date_format($date, 'd.m.Y H:i:s'); 	// со временем 
    $date_ru['HidmY']=date_format($date, 'H:i d.m.Y'); 
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

// Считает количество дней, прошедших с заданной даты
function days_from($str_date) {
	// $str_date в формате YYYY-mm-dd H:s:i
	$today = time();
	$timeunix = strtotime($str_date); 	
	return floor(($today - $timeunix) / 86400);
}

function time_from($str_date) {
	// $str_date в формате YYYY-mm-dd H:s:i
	$spend_time=array();
	$today = time();
	$timeunix = strtotime($str_date);
	$spend_time['days'] = floor(($today - $timeunix) / 86400);
	$spend_time['hours'] = floor(($today - $timeunix) / 3600);
	$spend_time['minutes'] = floor(($today - $timeunix) / 60);
	return $spend_time;
}

// Вывод даты с месяцем на русском языке
function rdate($param, $time=0) {
	// $param - формат вывода
	// $time - время в UNIX ормате TIMESTAMP
	if(intval($time)==0) $time=time();
	$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
	if(strpos($param,'M')===false) return date($param, $time);
		else return date(str_replace('M',$MonthNames[date('n',$time)-1],$param), $time);
}

// Определяем количество и тип единицы измерения
function showDate($time) { 
  $time = time() - $time;
  if ($time < 60) {
    return 'меньше минуты назад';
  } elseif ($time < 3600) {
    return dimension((int)($time/60), 'i');
  } elseif ($time < 86400) {
    return dimension((int)($time/3600), 'G');
  } elseif ($time < 2592000) {
    return dimension((int)($time/86400), 'j');
  } elseif ($time < 31104000) {
    return dimension((int)($time/2592000), 'n');
  } elseif ($time >= 31104000) {
    return dimension((int)($time/31104000), 'Y');
  }
}

// Определяем склонение единицы измерения
function dimension($time, $type) { 
  $dimension = array(
    'n' => array('месяцев', 'месяц', 'месяца', 'месяц'),
    'j' => array('дней', 'день', 'дня'),
    'G' => array('часов', 'час', 'часа'),
    'i' => array('минут', 'минуту', 'минуты'),
    'Y' => array('лет', 'год', 'года')
  );
    if ($time >= 5 && $time <= 20)
        $n = 0;
    else if ($time == 1 || $time % 10 == 1)
        $n = 1;
    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
        $n = 2;
    else
        $n = 0;
    return $time.' '.$dimension[$type][$n]. ' назад';

}

// Поиск аватара
function user_photo($avatar) {
	if (isset($avatar) && !empty($avatar) && !(trim($avatar)=="") && !is_null($avatar)) {		
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/uploads/images/users/".$avatar)) 
			return "/web/uploads/images/users/".$avatar;		
	} 	
	return "/web/uploads/images/users/nophoto.jpg";
}

// функция замены ключей массива
function change_key($key, $new_key, &$arr, $rewrite=true){
    if(!array_key_exists($new_key,$arr) || $rewrite){
        $arr[$new_key]=$arr[$key];
        unset($arr[$key]);
        return true;
    }
        return false;
}

// функция формирования нового массива с заменой ключей
function change_key_new($arr, $field_id_name){
	$arr_new=array();
	foreach($arr as $key=>$v) {
       $arr_new[$v[$field_id_name]] = $arr[$key];
	}	
	return $arr_new;
}


function sort_files($files) {
	$files_sort = array();
	foreach($files as $k => $l) {
		foreach($l as $i => $v) {
			$files_sort[$i][$k] = $v;
		}
	}		
	//$files = $files_sort;
	return $files_sort;
}

// возвращает процент роялти от суммы заказа
function get_royalty($summ) {
	switch ($summ) {
    case ($summ>0 and $summ<=30000):
       return 0.1;
       break;
     case ($summ>30000 and $summ<=70000):
       return 0.08;
       break;
     case ($summ>70000 and $summ<=110000):
       return 0.06;
       break;
     case ($summ>110000 and $summ<=150000):
       return 0.05;
       break;
     case ($summ>150000 and $summ<=1000000):
       return 0.03;
       break;
     case ($summ>1000000):
       return 0.01;
       break;                      
	}
}		