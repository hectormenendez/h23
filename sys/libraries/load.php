<?php if(!defined('OK')) die('<h1>403</h1>');

class Load {
	
	private static $_VAR = array(); // variables to be extracted inside the loaded files.
	
	public static function view($name=false, $vars=false, $return=false){
		return self::_loadfile($name, $vars, $return);
	} 
	
	public static function file($name=false, $vars=false, $return=false){
		return self::_loadfile($name, $vars, $return, true);
	}
		
	/**
	 * This function is used to load views and files.
	 * Variables are prefixed with __ to avoid symbol collision with
	 * variables made available to view files
	**/
	private static function _loadfile($name, $vars, $__return, $ispath=false){
		//  we make sure that vars is an array even if it's empty.
		if (!is_array($vars)) $vars = array();
		//  if $name refers to a file located in the views directory
		//  or to an absolute file path.
		if (!$ispath){
			// Add the views directory path and the default
			// file extension if $name of doesn't contain one
			$__path = pathinfo($name, PATHINFO_EXTENSION)!=''? VIEW.$name : VIEW.$name.EXT;
		} else $__path = $name;
		if(!file_exists($__path)) Core::error('404MSG','LIBTIT', array(__CLASS__,$name));
		// Extract variables to file (if any).
		// Note: We're merging the $vars array with the self::$_VAR array
		// so we can use either of the two methods available for accessing these vars.
		extract(self::$_VAR = array_merge(self::$_VAR, $vars));
		// Unset variables so they don't interfere with files' vars.
		unset($name,$vars,$ispath);
		// Start buffering
		ob_start();
		// Forcing PHP Short Tags
		// Note: I recommend not to use this feature, if your PHP installation
		// doesn't support it natively and you plan to code a complex application.
		// It's wasting processing time just for avoiding to write some chars. don't be lazy!
		if ((bool)@ini_get('short_open_tag')===false && Core::config('force_short_tags')===true)
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($__path))).'<?php ');
		else require($__path);
		// return the file data if requested
		if ($__return){
			$b = ob_get_contents();
			ob_end_clean();
			return $b;
		}
		// Flushing the buffer
		// In order to allow views to be nested within other views, we need to flush
		// the content back out whenever we are beyond the first level of Output Buffering
		// So it can be seen and included properly by the first included template and any subsequent ones. Oy!
	    if(!Core::obflush()) {
	    	$c = ob_get_contents();
	    	ob_end_clean();
	    	Output::append($c);
	    }
	}

}

?>