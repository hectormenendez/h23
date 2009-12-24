<?php
/**
 *  H23 FRAMEWORK
 *  @author		Héctor Menendez [23@giro.cc]
 *  @version 		0.0.688 R10 [alpha]
 *  @copyright 	GIRO Diseño 2009
**/

//  Framework Version
define('VER', '0.0.688');

// -------------------------------------------------------------  Preparations

	// set this to true if you want to be able to debug the env vars using http://urltoframework.com?phpinfo
	// this MUST BE SET TO FALSE in production servers.
	define('PHPINFO',false);
	//  Starts simple benchmarking
	define('BMK', microtime(true));
	// define if this is an external file loading the framework
	define('_EX', isset($_EXTERNAL)? true : false );

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

	//  Extension for framework files
	define('EXT', '.php');
	// Full path for the root dir.
	define('ROOT', pathinfo($_SF, PATHINFO_DIRNAME)._SH);
	//  Base name of this file
	define('BASE', pathinfo($_SF, PATHINFO_BASENAME));

	//  Path relative to the web root
	//  NOTE: continuing with the apache-for-windows compatibility issue, we'll look for backslashes so they will be replaced,
	//  since the PATH constant will be used for URI related operations. (again I'm not really sure if this is even necessary).
	$_PT = str_replace($_DR,'/', ROOT);
	define('PATH', isset($_ISWIN)? str_replace("\\",'/', $_PT) : $_PT);
	// Absolute path to the system folder
	define('SYS', ROOT.'sys'._SH);
	//  Absolute path to the application folder
	define('APP', ROOT.'app'._SH);
	// Absolue path to the temporary files folder
	// NOTE: for security reasons it is recommended to set this to a dir outside the webroot.
	define('TMP', ROOT.'tmp'._SH);
	// URL to the root of the framework
	define('URL', 'http://'.$_SERVER['HTTP_HOST'].PATH);
	//  Url of the includes folder
	define('INC', URL.'inc/');
	define('INK', urlpath(INC));

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

	//  Get the initial Output Buffer Level
	define('OBLVL',ob_get_level()+1);
	//  This serves as a checkpoint for the included files, so we can be sure the framework is loaded on'em
	define('OK', true);


	if (!_EX){
		// this const is used by the Core::varname() method
		// and MUST NEVER BE USED, EVER.
		// we avoid loading it when the framework is called from an external file.
		define('VRND', md5(rand()));

// ------------------------------------------------------------- show debug info

		// if phpinfo is enabled show server info upon request.
		if (PHPINFO===true && isset($_GET['info'])){
			$foo = get_defined_constants(true);
			echo "<pre>";
			print_r($foo['user']);
			echo "</pre>";
			phpinfo();
			die;
		}

// -------------------------------------------------------------  Check Filesystem Structure
		// We will check the filesystem structure the first time the
		// framework runs. After that, we will create a dummy file inside
		// the TMP folder holding the last time a check up was made.
		// This way the FW will check the file structure integrity only once in a while
		// (we don't want to over do anything, right?)

		$_F = array();
		if (!file_exists($_F['path']=TMP.'system.chk')) {
			// force checking enabled
			$_F['fchk'] = true;
			file_put_contents($_F['path'],BMK);
			// first we have to be sure that the TMP dir exists and is writable
			// if the last.chk point doesn't exist, create it and insert the execution time of this script.
			foreach(array(TMP,urlpath(INC)) as $r){
				if (!file_exists($r) || !is_writable($r)) error(_SH.rmroot(urlpath($r." must exist, and be writable.")));
			}
		// force checking disabled, calculate remaining time.
		} else $_F['fchk'] = false;
		// get the last check timestamp.
		$_F['bmk'] = (float)file_get_contents($_F['path']);
		// days to wait before checking again?
		$_F['days'] = 7;
		if ($_F['fchk'] || BMK >= ($_F['bmk']*($_F['days']*86400))){
			// required dirs
			$_F['req'] = array('SYS','APP','INC','CORE','LIBS','HELP','TMPL','CTRL','MODL','VIEW','CONT');
			// make dir if not found and make writable (error if exist and not writable)
			$_F['wri'] = array('TMP','INC','CACH','SESS','TXML','JS','IMG','CSS','SWF','HTC','XML',);
			foreach ($_F['req'] as $r)
				if (!file_exists(urlpath(constant($r)))) error(_SH.rmroot(urlpath(constant($r)." must exist.")));
			foreach ($_F['wri'] as $r){
				if (!file_exists(urlpath(constant($r)))) {
					if (!@mkdir(urlpath(constant($r)),0744)) error(_SH.rmroot(urlpath(constant($r)." couldn't be created.")));
				} elseif (!is_writable(urlpath(constant($r)))) {
					if (!@chmod(urlpath(constant($r)),0744)) error(_SH.rmroot(urlpath(constant($r)." isn't writable.")));
				};
			};

			// now we need to be sure that the INC helper file, exists.
			if (!file_exists(INK.'inc'.EXT)) {
				// if the template does not exists, send an error
				if (!file_exists(TMPL.'inc'.EXT)) error("INC template doesn't exists.");
				file_put_contents(urlpath(INC).'inc'.EXT, str_replace('%PATH%',ROOT.BASE,file_get_contents(TMPL.'inc'.EXT)));
			};

		};
		unset($_F);
		// generate htaccess (beta)
		$_hta = ROOT.'.htaccess';
		$_htf = TMPL.'htaccess'.EXT;
		if (!file_exists($_hta)) {
			include CORE.'config'.EXT;
			$_htf = file_get_contents($_htf);
			$_htf = preg_replace('/\<\?.*\?\>\n*/','',$_htf);

			if (isset($_CFG['charset'])) $_htf = str_replace('%CHARSET%', strtoupper($_CFG['charset']), $_htf);
			 $_htf = str_replace('%ROOT%',PATH, $_htf);
			file_put_contents($_hta,$_htf);
			unset($_CFG);
		};
	};

// -------------------------------------------------------------  Set the Core library

	// load the core library
	// NOTE: for security reasons, the core is loaded with an error control operator
	//		 making all the errors inside it, invisible. so if you plan to develop or
	//		 change something inside it, you must remove the @.
	if (!@include CORE.'core'.EXT) error('CORE unavailable');
	//  Set the autoload function for the libraries
	spl_autoload_register(array('Core','libraries'));
	//  Kill magic quotes
	set_magic_quotes_runtime(0);
	// Get every error except those who can't be managed by our error handler,
	// for those errors we will trigger the shutdown function.
	// I know you are thinking, why don't you just use level 0 since we have a custom error handler
	// and I say, that would not let us know when the user is using a error control operator (@)
	// to avoid showing any error.
	error_reporting((E_ALL|E_STRICT)^(E_ERROR|E_PARSE|E_CORE_ERROR|E_CORE_WARNING|E_COMPILE_ERROR|E_COMPILE_WARNING));
	// Custom PHP error Handler
	set_error_handler(array('Core','errorphp'));
	// Custom shutdown handler (used to show those errors thar errorphp isn't supposed to handle)
	register_shutdown_function(array('Core','shutdown'));
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

// -------------------------------------------------------------  Support Functions

	function urlpath($url){
		return str_replace(URL,ROOT,$url);
	}

	function rmroot($path){
		return str_replace(ROOT,'',$path);
	}

	function error($msg){
		unlink(TMP.'system.chk');
		die("<h1 style='color:#900'>$msg</h1>");
	}
?>
