<?php
/**
 *  H23 FRAMEWORK
 *  @author	Héctor Menendez [23@giro.cc]
 *  @version 0.0.662 [alpha]
 *	@copyright GIRO Diseño 2008
 *  NOTES:
 *  Log library hasn't been implemented yet.
 *  and everyday becomes less important with this kick ass trackback error functions I added. LOL
**/
$_CHECK = true;
// -------------------------------------------------------------  Preparations
	
	//  Starts simple benchmarking
	define('BMK', microtime(true));
	// define if this is an external file loading the framework
	define('_EX', isset($_EXTERNAL)? true : false );
	
	//  Comment out this when the framework goes live
	error_reporting(E_ALL|E_STRICT);
	
	// full path for this file
	$_SF = _EX? __FILE__ : $_SERVER['SCRIPT_FILENAME'];
	// obtain the Document Root for the web server
	$_DR = isset($_SERVER['DOCUMENT_ROOT'])?  $_SERVER['DOCUMENT_ROOT'] : substr($_SF,0,0-strlen($_SERVER['PHP_SELF']));
	// if apache is running on windows it will be using back slashes for paths,
	// we need to detect this and make a variable so we can use it whenever it's necessary
	if (strpos($_SF,$_SH='/')===false){
		$_ISWIN = true;
		$_SH = "\\";
		// we need to make sure that SF and DR have the same path format (slashes)
		// I'm not really sure if this is even necessary in these days, since the server constants return the path with 
		// unix style slashes all the time (at least for me), but you never now, maybe previous versions of apache will 
		// need this, if someone reads this and has the chance to test it, gimme feedback please.		
		$_DR = str_replace('/', $_SH, $_DR);
	}
	// we define this as a constant so it can be used in the libraries to avoid compatibility issues
	define('_SH', $_SH);
	// we make sure we always have a trailing slash on the document root
	if (substr($_DR,-1)!=_SH) $_DR.=_SH;

// -------------------------------------------------------------  Framework Constants

	//  Framework Version
	define('VER', '0.0.671');
	//  Extension for framework files 
	define('EXT', '.php');
	// Full path for the root dir.
	define('ROOT', pathinfo($_SF, PATHINFO_DIRNAME)._SH);
	//  Base name of this file 
	define('BASE', pathinfo($_SF, PATHINFO_BASENAME));
	//  Path relative to the web root 
	//  NOTE: continuing with the apache-for-windows compatibility issue, if there is any backslashes it will be replaced, 
	//  since the PATH constant will be used for URI related operations. (again I'm not really sure if this is even necessary).
	$_PT = str_replace($_DR,'/', ROOT);
	define('PATH', isset($_ISWIN)? str_replace("\\",'/', $_PT) : $_PT);
	// URL to the root of the framework
	define('URL', 'http://'.$_SERVER['HTTP_HOST'].PATH);
	// Absolute path to the system folder
	define('SYS', ROOT.'sys'._SH);
	//  Absolute path to the application folder
	define('APP', ROOT.'app'._SH);
	// Absolue path to the temporary files folder
	// NOTE: for security reasons it is recommended to set this to a dir outside the webroot.
	define('TMP', ROOT.'tmp'._SH);
	//  Url of the includes folder
	define('INC', URL.'inc/');
	// Absolute path for INC
	define('INK', ROOT.substr($x=substr(INC,0,strrpos(INC,"/")),strrpos($x,"/")+1, strlen($x))._SH);
	//  Gets the initial Output Buffer Level 
	define('OBLVL',ob_get_level()+1);
	//  This serves as a checkpoint
	define('OK', true);
	
	//  Filesystem
	define('CORE', SYS.'core'._SH);
	define('LIBS', SYS.'libraries'._SH);
	define('HELP', SYS.'helpers'._SH);
	define('TMPL', SYS.'templates'._SH);
	define('CTRL', APP.'controllers'._SH);
	define('MODL', APP.'models'._SH);
	define('VIEW', APP.'views'._SH);
	define('CONT', APP.'content'._SH);
	//  Multimedia includes
	define('JS' , INC.'js/');
	define('IMG', INC.'img/');
	define('CSS', INC.'css/');
	define('SWF', INC.'swf/');
	define('HTC', INC.'htc/');
	define('XML', INC.'xml/'); 
	//  Temporary folders
	define('CACH', TMP.'cache'._SH);
	define('SESS', TMP.'session'._SH);
	define('TXML', TMP.'xml'._SH);
	// this const is used by the Core::varname() method
	// and MUST NEVER BE USED, EVER.
	if (!_EX) define('VRND', md5(rand()));
	

// -------------------------------------------------------------  some DEBUGGING
/*
	$foo = get_defined_constants(true);
	print_r($foo['user']);
	die;
*/
// -------------------------------------------------------------  Check Filesystem Structure
	// We will check the filesystem structure the first time the
	// framework runs, after that, we will create a dummy file inside
	// the TMP folder holding the last time a check up was made. 
	// This way the FW will check the file structure integrity only if 
	// more than 24 hours have passed since the last check 
	// (we don't want to over do anything, right?)
	if(!_EX) {
		if (!file_exists($_FP=TMP.'filesystem.chk')) $_CHECK = file_put_contents($_FP,BMK);
		$_FC = file_get_contents($_FP);
		if (((BMK-86400)>=$_FC)||isset($_CHECK)){ //86400=24hrs
			// directory constants
			$dir = array(
				'SYS','APP','INK','TMP','CORE','LIBS','HELP', 'TMPL',
				'CTRL','MODL','VIEW','CONT','CACH','SESS','TXML');
			// directories with write access required
			$wri = array('TMP','SESS','CACH','TXML');
			foreach ($dir as $x) if (!file_exists(constant($x)))
				die("<h1 style='color:#900'>Directory $x does not exist</h1>");
			foreach ($wri as $x) if (!is_writable(constant($x)))
				die("<h1 style='color:#900'>Directory $x needs to be writable</h1>");
		}
		// now we need to be sure that the INC helper exists
		if (!file_exists(INK.'inc'.EXT)) {
			// if the template does not exists, we send an error
			if(!file_exists(TMPL.'inc'.EXT)) die ("<h1 style='color:#900'>INC template doesn't exists</h1>");
			file_put_contents(INK.'inc'.EXT, str_replace('%PATH%',ROOT.BASE,file_get_contents(TMPL.'inc'.EXT)));
		}
	}

// -------------------------------------------------------------  Set the Core library
	
	// load the core library
	// NOTE: for security reasons, This library is loaded with a try-catch exception
	//       handler, so any errors on the core won't show up.
	//		 In other words, if you plan to edit or upgrade the core, you must load it
	//		 with a normal include, so if there are errors you can debug them.
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

	
// -------------------------------------------------------------  Launch Framework	
// Process the Routing and show Output only if this isn't an EXTERNAL call

	if(!_EX) {
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