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