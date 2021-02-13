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

// отправка email
function send_email($email,$text){	
	// отправка смс на email с текстом  $text
	$email_from = \Yii::$app->params['adminEmail'];
	$email_subject = \Yii::$app->params['email_subject'];	
	
	Yii::$app->mailer->compose()
	    ->setFrom($email_from)
	    ->setTo($email)
	    ->setSubject($email_subject)
	    ->setTextBody($text)
	    ->setHtmlBody($text)
	    ->send();	

	    Yii::$app->session->setFlash('send_code', $text. ' Письмо отправлено');
	    return;	 
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

        
	

