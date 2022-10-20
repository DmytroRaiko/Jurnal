<?php

function can_upload($file){

	if($file['size'] > 4194304){
		return 0;
	}
	$getMime = explode('.', $file['name']);
	$mime = strtolower(end($getMime));
	$types = array('jpg', 'png', 'jpeg');
	
	if(!in_array($mime, $types))
		return 0;
	
	return 1;
}

?>