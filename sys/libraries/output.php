<?php if(!defined('OK')) die('<h1>403</h1>');

class Output {

	private static $_ISC = false;   // wheter the current output call is a cached file or not.
	private static $_FOU;		    // final output
	private static $_CEX = 0;	    // wheter the cache has expired or not.
	private static $_HDR = array(); // Headers array

	public static function _construct(){
		//  check if there's a cached version of the requested page.
		//  if so, display it and exit.
		if (self::_cachedisplay()) exit;
	}

	/**
	 *  ENABLE CACHE
	 *  Set the cache expiration time
	 *  if set to 0, cache will be disabled.
	**/
	public static function cache($time){
		self::$_CEX = !is_numeric($time)? 0 : $time;
	}

	public static function iscache(){
		return self::$_ISC;
	}

	public static function headers($header=false){
		if (!$header) return self::$_HDR;
		self::$_HDR[]=$header;
	}

	public static function get(){
		return self::$_FOU? self::$_FOU : '';
	}

	public static function display($output=false){
		// Set the output data
		if (!$output) $output = self::$_FOU;
		// check if cache has expired, and if so, write new cache.
		if (self::$_CEX>0) self::_cachewrite($output);
		// send headers if available
		if (count(self::$_HDR)>0) foreach(self::$_HDR as $h) @header($h);
		// if the controller contains a function named '_output'
		// send the output there, otherwise echo it.
		$ctl = Core::controller();
		if (method_exists($ctl,'_output')) call_user_func(array($ctl,'_output'));
		else echo $output;
	}

	public static function append($output){
		if (!self::$_FOU) self::$_FOU = $output;
		else self::$_FOU .= $output;
	}

	private static function _cachewrite($output){
		// Build the file path and open the file
		$path = self::_cachepath();
		if (!$file = @fopen($path,'wb')) Core::error('403MSG','LIBTIT',array(__CLASS__,'cache'));
		// determine the cache expiration time
		$exp = (time()+(self::$_CEX*60));
		// lock the file so it can only be written
		flock($file, LOCK_EX);
		// write the timestamp and the output
		fwrite($file, $exp.'TS--->'.$output);
		// unlock and close the cache file
		flock($file, LOCK_UN);
		fclose($file);
		// set write permissions to newly created cache file
		@chmod($path,0777);
	}

	private static function _cachedisplay() {
		// Build the file path
		$path = self::_cachepath();
		// return false if cache file doesn't exist or can't open file.
		if (!@file_exists($path) || !$file = @fopen($path,'rb')) return false;
		// lock so it can only be read.
		flock($file, LOCK_SH);
		// get the cache file contents.
		$cache = ''; $size  = filesize($path);
		if ($size>0) $cache = fread($file,$size);
		// unlock and close the cache file
		flock($file, LOCK_UN);
		fclose($file);
		// return false if a timestamp can't be found
		// and capture matches so we can use them below
		if (!preg_match("/(\d+TS--->)/", $cache, $match)) return false;
		// if file has expired delete it.
		if (time()>=trim(str_replace('TS--->', '',$match[1]))) {
			@unlink($path);
			return false;
		}
		// specify that this is a cached output call
		self::$_ISC = true;
		// display the cache and return true
		self::display(str_replace($match[0],'',$cache));
		return true;
	}

	private static function _cachepath(){
		// if cache directory doesn't exists create it.
		if (!is_dir(CACH)) mkdir(CACH,0777);
		return CACH.md5(URL.BASE.Uri::string());
	}
}
?>
