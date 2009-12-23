<?php if(!defined('OK')) die('<h1>403</h1>');

class Router {

	private static $_RTR;  //  Routes Array
	private static $_CTR;  //  Default Controller
	private static $_DIR;  //  Directory of controller (if in subfolder)
	private static $_CLS;  //  Current Controller (Class)
	private static $_MTD;  //  Current Action (Method)

	/**
	 *  STATIC CONSTRUCTOR
	 *  custom method that will be called whenever
	 *  a static method of this class is called.
	**/
	public static function _construct(){
		//  Load the routes file
		if (!file_exists(CORE.'routes'.EXT)) Core::error404('routes');
		include CORE.'routes'.EXT;
		if (!isset($_RTR) || !is_array($_RTR)) Core::error('RTRFRM','LIBTIT',__METHOD__);
		self::$_RTR = &$_RTR;
		unset($_RTR);
		//  Get the default controller
		self::$_CTR = Core::config('controller');
		// Is there an uri string? if not, show default controller
		if (Uri::fetch()==''){
			//  If no controller is specified, throw an error.
			if (!self::$_CTR) Core::error('RTRCTR','LIBTIT',__METHOD__);
			//  set the current class and method (Controller & Action respectively)
			self::_set(array(self::$_CTR,'index'));
			return;
		}
		//  remove the url_suffix if needed
		Uri::removesuffix();
		//  compile the segments into an array
		Uri::explode();
		// parse any custom routing that it may exist
		self::_parsecustom();
		// Re-index the segment array so that it starts with 1 rather than 0
		Uri::reindex();
	}

	/**
	 *  RETURN CURRENT CONTROLLER (CLASS)
	**/
	public static function classname(){
		return self::$_CLS;
	}

	public static function classpath(){
		$s = self::$_DIR? self::$_DIR.'/' : '';
		return CTRL.$s.self::$_CLS.EXT;
	}

	/**
	 * RETURN CURRENT ACTION (METHOD)
	**/
	public static function methodname(){
		return self::$_MTD;
	}


	/**
	*  PARSE ROUTES
	*  This function matches any routes that may exist
	*  to determine if the class/method needs to be remapped.
	**/
	private static function _parsecustom(){
		$seg = Uri::segments();
		// Are there any custom routes?
		if (count(self::$_RTR)==0) {
			self::_set($seg);
			return;
		}
		// Turn the segment array into an URI string
		$uri = implode('/', $seg);
		$num = count($seg);
		// return if literal match is found.
		if (isset(self::$_RTR[$uri])){
			self::_set(explode('/', self::$_RTR[$uri]));
			return;
		}
		// Get the available wildcards from the library
		$wc = Core::config('routing_wildcards');
		if (!is_array($wc)) Core::error('ARRTYP','LIBTIT',array(__CLASS__,'routing_wildcards'));
		// Search through the segment array for wild-cards
		foreach(self::$_RTR as $key=>$val){
			// convert wild cards to regex
			if (count($wc)>0) foreach ($wc as $k=>$v) $key = str_replace($k,$v,$key);
			// check for regex matches
			if (preg_match('#^'.$key.'$#', $uri)){
				// do we have a back-reference
				if (strpos($val,'$')!==false && strpos($key,'(')!==false)
					$val = preg_replace('#^'.$key.'$#',$val, $uri);
				self::_set(explode('/',$val));
				return;
			}
		}
		// there was no matching route follow default route
		self::_set($seg);
	}

	/**
	 *  SET THE ROUTE
	 *  This function takes an array of URI segments as
	 *  input, and sets the current class/method
	**/
	private static function _set($segments = false){
		// we check the segments array
		$segments = self::_validate($segments);
		if (count($segments)==0) return;
		self::$_CLS = $segments[0];
		// Set the corresponding method.
		if (isset($segments[1])) self::$_MTD = $segments[1];
		else self::$_MTD = $segments[1] = 'index';
		// Update the routed segments array
		// if there's no custom routing this will be the same as Uri::segments();
		Uri::routedsegments($segments);
	}

	/**
	 *  VALIDATE THE ROUTE
	 *  Validates the supplied segments.
	 * Attempts to determine the path to the controller.
	**/
	private static function _validate($segments = false){
		//  if no segments are specified we use the current class and method.
		if (!$segments) $segments = array(self::$_CLS, self::$_MTD);
		if (!is_array($segments)) Core::error('ARRTYP','LIBTIT',array(__METHOD__,'segments'));
		// does the requested controller file exist?
		if (file_exists(CTRL.$segments[0].EXT)) return $segments;
		// in a subfolder ?
		if (is_dir(CTRL.$segments[0])){
			// Set the current dir and remove it from the array
			self::$_DIR = $segments[0];
			$segments = array_slice($segments,1);
			// does the requested controller file exists in subfolder?
			if (count($segments)>0) {
				if (!file_exists(CTRL.self::$_DIR.'/'.$segments[0].EXT)) Core::error404();
			} else {
				if (!file_exists(CTRL.self::$_DIR.'/'.self::$_CTR.EXT)) Core::error('RTRCTR','LIBTIT',__CLASS__);
				// we set the default controller
				self::$_CLS = self::$_CTR;
				self::$_MTD = 'index';
			}
			return $segments;
		}
		// if default controller doesn't exists throw an error instead of the 404.
		if (!file_exists(CTRL.self::$_CTR.EXT)) Core::error('RTRCTR','LIBTIT',__CLASS__);
		Core::error404(Uri::string());
	}

}
?>
