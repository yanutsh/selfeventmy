<?php 
// Считает количество дней, прошедших с заданной даты

function days_from($str_date) {
	// $str_date в формате YYYY-mm-dd H:s:i
	$today = time();
	$timeunix = strtotime($str_date); 	
	return floor(($today - $timeunix) / 86400);
}	