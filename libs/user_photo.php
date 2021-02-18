<?php
function user_photo($avatar) {

	//return ('аватар1='.$avatar); die;
		
	if (isset($avatar) && !empty($avatar) && !(trim($avatar)=="") ) {

		//if (file_exists($_SERVER['DOCUMENT_ROOT'].SUB_PATH."/web/uplouds/users/".$avatar) ) 
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/uploads/images/users/".$avatar)) 
			return "/web/uploads/images/users/".$avatar;		
	} 
	
	//return $_SERVER['DOCUMENT_ROOT'].SUB_PATH."/web/uplouds/users/nophoto.jpg";
	return "/web/uploads/images/users/nophoto.jpg";
}

?>