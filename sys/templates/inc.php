<?php if(!isset($_TYPE)) die('<h1>403</h1>');
	// The purpose of this SELF-GENERATED file is to set the headers for the included files,
	// and to make the framework available for these.
	// DO NOT MODIFY THIS FILE UNLESS YOU KNOW WHAT YOU'RE DOING.
	// NOTE: If you mess up, and modified this file and now your dynamic includes aren't working right
	//       just delete this file, so it can be generated again with the correct info.

	// set the current content type.
	header('Content-Type: '.$_TYPE);

	// set the cache control.
	// if nothing is specified the file won't be cached hence reload everytime

	// the file specified to cache the file
	// if the user set the var to true the file will be cached for 24 hours.
	if ((isset($_CACHE) && is_int($_CACHE) && $_CACHE>0) || $_CACHE===true) {
		if ($_CACHE===true) $_CACHE=24;
		header('Cache-Control: must-revalidate');
		header('Expires: '.gmdate('D, d M Y H:i:s', time()+(3600*$_CACHE)).' GMT');
	} else { // no cache
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Pragma: no-cache');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	}

	// this variable tells the framework to avoid loading the Output class again.
	$_EXTERNAL = true;

	// load the framework
	if (strpos('image',$_TYPE) === false)  include ('%PATH%');
?>
