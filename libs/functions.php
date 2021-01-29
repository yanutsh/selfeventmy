<?php 

function debug($data,$die=true){
	echo "<pre>";
	print_r($data);
	echo"<pre>";
	if ($die) die;
}	