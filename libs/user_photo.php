<?php
function user_photo($avatar) {
	if (isset($avatar) && !empty($avatar) && !(trim($avatar)=="") && !is_null($avatar)) {		
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/uploads/images/users/".$avatar)) 
			return "/web/uploads/images/users/".$avatar;		
	} 	
	return "/web/uploads/images/users/nophoto.jpg";
}