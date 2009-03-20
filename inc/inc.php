<?php 
	// set the current content type.
	// if nothing specified text/plain will be used.
	header('Content-Type: text/'.(isset($_TYPE)? $_TYPE: 'plain')); 
	// set the core variable so index.php doesn't load the core, and therefore a recursion.
	$_EXTERNAL = true;
	// the name of the main file.
	$f['fl'] = '/index.php';
	// how deep will the function search for the file.
	$f['dp'] = 3;
	// we set the current directory, and start the search, if succesful load the file.
	$f['dr'] = pathinfo(__FILE__, PATHINFO_DIRNAME);
	for ($i=0; $i<$f['dp']; ++$i){
		if (file_exists($f['pt']=($f['dr']=substr($f['dr'],0, strrpos($f['dr'],'/'))).$f['fl'])) break;
		else $f['pt']=false;
	} if ($f['pt']) include $f['pt']; unset($f);
	
	function png32($url,$method=false){
		if(!$method) $method="scale"; else $method="image";
		echo "background:none; ";
		echo "filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=$method, src='$url');";
	};

?>