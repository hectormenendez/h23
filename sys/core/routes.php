<?php if(!defined('OK')) die('<h1>403</h1>');
	
	// if the uri contains an number it means that
	// the user is trying to reach a magazine number.
	// if afterwards contains minimum 4 chars it means that it is looking for a section
	// if afterwards cibtaubs minimun 4 chars it means that it is looking for a subsection
	$_RTR['(\d{0,4})'] = "main/index/$1";
	$_RTR['(\d{0,4})/([a-z\-]{4,})'] = "main/index/$1/$2";
	$_RTR['(\d{0,4})/([a-z\-]{4,})/([a-z\-]{4,})'] = "main/index/$1/$2/$3";

	$_RTR['test/(.*)'] = "test/$1"

	
?>