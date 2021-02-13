<?php
function user_photo($avatar) {
		
	if (isset($avatar) && !empty($avatar) && !(trim($avatar)=="") ) {

		//if (file_exists($_SERVER['DOCUMENT_ROOT'].SUB_PATH."/web/uplouds/users/".$avatar) ) 
		if (file_exists($_SERVER['HTTP_HOST']."/web/uploads/images/users/".$avatar) ) 
			return $avatar;		
	} 
	
	//return $_SERVER['DOCUMENT_ROOT'].SUB_PATH."/web/uplouds/users/nophoto.jpg";
	return "/web/uploads/images/users/nophoto.jpg";
}

?>