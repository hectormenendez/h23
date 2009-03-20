<?php
/**
 *  H23 FRAMEWORK
 *  @author	Héctor Menendez
 *  NOTES:
 *  Log library hasn't been implemented yet.
 *  and everyday becomes less important.
**/
	
// -------------------------------------------------------------  Preparations

	//  Comment out when development finishes
	//error_reporting(E_ALL|E_STRICT);
	
	$_SF = $_SERVER['SCRIPT_FILENAME'];
	// We have to make sure we have the correct location of the web server's document root.
	// we can't rely on the __FILE__ constant, because we are going to reuse this script later
	$_DR = !isset($_SERVER['DOCUMENT_ROOT']) ? 
		str_replace('\\','/',substr($_SF,0,0-strlen($_SERVER['PHP_SELF']))) : $_SERVER['DOCUMENT_ROOT'];
	// make sure we always have a trailing slash
	if (substr($_DR,-1)!='/') $_DR.='/';

// -------------------------------------------------------------  Framework Constants

	//  Starts simple benchmarking
	define('BMK', microtime(true));
	//  Framework Version
	define('VER', '0.0.66');
	//  Extension for framework files 
	define('EXT', '.php');
	// Full path for the root dir.
	define('ROOT', pathinfo(__FILE__, PATHINFO_DIRNAME).'/');
	//  Base name of this file 
	define('BASE', pathinfo(__FILE__, PATHINFO_BASENAME));
	//  Path relative to the web root
	define('PATH', str_replace($_DR,'/',ROOT));
	// URL to the root of the framework
	define('URL', 'http://'.$_SERVER['HTTP_HOST'].PATH);
	// Absolute path to the system folder
	define('SYS', ROOT.'sys/');
	//  Absolute path to the application folder
	define('APP', ROOT.'app/');
	// Absolue path to the temporary files folder
	// NOTE: for security reasons it is recommended to set this to a dir outside the webroot.
	define('TMP', ROOT.'tmp/');
	//  Url of the includes folder
	define('INC', URL.'inc/');
	//  Gets the initial Output Buffer Level 
	define('OBLVL',ob_get_level()+1);
	//  This serves as a checkpoint
	define('OK', true);
	
	//  Filesystem
	define('CORE', SYS.'core/');
	define('LIBS', SYS.'libraries/');
	define('TMPL', SYS.'templates/');
	define('CTRL', APP.'controllers/');
	define('MODL', APP.'models/');
	define('VIEW', APP.'views/');
	define('CONT', APP.'content/');
	//  Multimedia includes
	define('JS' , INC.'js/');
	define('IMG', INC.'img/');
	define('CSS', INC.'css/');
	define('SWF', INC.'swf/');
	define('HTC', INC.'htc/');
	define('XML', INC.'xml/'); 
	//  Temporary folders
	define('CACH', TMP.'cache/');
	define('SESS', TMP.'session/');
	define('TXML', TMP.'xml/');
	// this const is used by the Core::varname() method
	// and MUST NEVER BE USED, EVER.
	if (!isset($_EXTERNAL)) define('VRND', md5(rand()));

	//if (isset($_EXTERNAL)) die($_SERVER['PHP_SELF']); else echo URL;

// -------------------------------------------------------------  Set the Core library
	
	// load the core library
	// * NOTE : if you are editing the core library, it's recommended that you
	//          include the file without the try catch exception handler, because
	//          if there's a php error there, it won't show up.
	include CORE.'core'.EXT;
	//try { if (!@include CORE.'core'.EXT) throw new Exception; } 
	//catch (Exception $e){ die('<h1 style="color:#900">Core Library not found</h1>');}
	
	//  Set the autoload function for the libraries
	spl_autoload_register(array('Core','libraries'));
	//  Custom PHP error Handler
	set_error_handler(array('Core','errorphp'));
	//  Kill magic quotes
	set_magic_quotes_runtime(0);
	// set timezone 
	date_default_timezone_set(Core::config('time_zone'));

	// start the session
	Session::start();

// -------------------------------------------------------------  Check Filesystem
// We will check the filesystem structure the first time the
// framework runs, after that, we will create a dummy file inside
// the TMP folder holding the last time a check up was run. so we
// can make this check once a day. 
// (i'm avoiding this check everytime the fw runs, so i can save a 
// few miliseconds of execution)
	
	if (!file_exists($fa=TMP.'filesystem.chk')) file_put_contents($fa,BMK);
	$fb = file_get_contents($fa);
	if ($fb==BMK || (BMK-86400)>=$fb){ //86400=24hrs
		// directory constants
		$dir = array(
			'PATH','SYS','APP','TMP','CORE','LIBS','TMPL',
			'CTRL','MODL','VIEW','CONT','CACH','SESS','TXML');
		// directories with write access required
		$wri = array('TMP','SESS','CACH','TXML');
		foreach ($dir as $x) if (!file_exists(constant($x))) 
			die("<h1 style='color:#900'>Directory $x does not exist</h1>");
		foreach ($wri as $x) if (!is_writable(constant($x)))
			die("<h1 style='color:#900'>Directory $x is not writable</h1>e");
	}


// -------------------------------------------------------------  Launch Framework	
// Process the Routing and show Output only if this isn't an EXTERNAL call

	if(!isset($_EXTERNAL)) {
		//  Include the corresponding class & method
		include Router::classpath();
		$class  = Router::classname();
		$method = Router::methodname();
		$uri = Uri::string();
		// Check that no system functions or methods starting
		// with an underscore can be called via the URI.
		if (array_key_exists( strtolower($method), Core::libraries() ) ||  substr($method,0,1)=='_')
			Core::error404($uri);
		// instantiate the controller (and run its constructor)	
		$class = Core::controller($class);
		// Call the remapping function if it's present in the controller
		if (method_exists($class,'_remap')) call_user_func(array($class,'_remap'));
		else {
			if (!method_exists($class,$method)) Core::error404($uri);
			// Call the requested method. Any URI segments present 
			// (besides the controller/action) will be passed to the method for convenience			
			call_user_func_array(array($class,$method), array_slice(Uri::routedsegments(),2));
		}
		// send final output
		Output::display();
	}
	
?>