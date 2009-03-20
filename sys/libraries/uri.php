<?php if(!defined('OK')) die('<h1>403</h1>');

class Uri {
	
	private static $_STR = ''; // Uri string.
	private static $_SEG;      // Uri segments array
	private static $_RSG;	   // Uri routed segments array

	public static function fetch(){
		$uri = strtoupper(Core::config('uri_protocol'));
		if ($uri =='AUTO'){
			//  Start checking the different server variables
			if (self::_check('PATH_INFO')) return self::$_STR;
			if (self::_check('QUERY_STRING')) return self::$_STR;
			if (self::_check('ORIG_PATH_INFO')) return self::$_STR;
			//  If everything fails, parse request uri.
			self::$_STR = self::_parse_request();
		} else {
			if ($uri=='REQUEST_URI'){ return self::_parse_request(); }
			self::$_STR = isset($_SERVER[$uri])? $_SERVER[$uri] : @getenv($uri);
		}
		// if the uri contains only a slash we clean it
		if (self::$_STR=='/') self::$_STR='';
		return self::$_STR;
	}
	
	// Returns the URI segment based on the number provided.
	public static function segment($n='') {
		return !(isset(self::$_SEG[$n]))? false : self::$_SEG[$n];
	}
	
	public static function segments($value = false){
		if (!$value) return self::$_SEG;
		self::$_SEG = $value;
	}
	
	public static function routedsegments($value = false){
		if (!$value) return self::$_RSG;
		self::$_RSG = $value;
	}
	
	public static function string(){
		return self::$_STR;	
	}
	
	/** 
	 *  Removes the URL suffix if needed.
	**/
	public static function removesuffix(){
		$sfx = Core::config('url_suffix');
		if ($sfx!=''){
			self::$_STR = preg_replace('|'.preg_quote($sfx)."$|",'',self::$_STR);
		}
	}
	
	/**
	 *  EXPLODE THE URI SEGMENTS
	 *  the individual segments will be stored in the self::$_SEG array.	
	**/
	public static function explode(){
		self::$_SEG = array();
		foreach(explode('/',preg_replace("|/*(.+?)/*$|","\\1",self::$_STR)) as $val){
			// Filter segments for security
			$val = trim(self::_filter($val));
			if ($val!='') self::$_SEG[] = $val;
		}
	}
	/** 
	 * FILTER MALICIOUS CHARACTERS 
	**/
	private static function _filter($str=''){
		$uch = Core::config('uri_chars');
		if ($uch!='' && $str!='') $str = preg_replace('|'.preg_quote($uch)."$|",'',$str);
		return $str;
	}
	
	/**
	 *  RE-INDEX SEGMENT
	 *  This function re-indexes the $this->segment array so that it
	 *  starts at 1 rather then 0.  Doing so makes it simpler to
	 *  use functions like $this->uri->segment(n) since there is
	 *  a 1:1 relationship between the segment array and the actual segments.
	**/
	public static function reindex(){
		//  Is the routed segment array different from the main segment?
		//  it has to be tested both ways since PHP only returns values arr1 that are not in arr2
		$diff = (array_diff(self::$_RSG, self::$_SEG) != array_diff(self::$_SEG, self::$_RSG))? true : false;
		$i = 1;
		foreach (self::$_SEG as $seg) self::$_SEG[++$i] = $seg;
		unset(self::$_SEG[0]);
		if (!$diff) self::$_RSG = self::$_SEG;
		else {
			$i=1;
			foreach (self::$_RSG as $seg) self::$_RSG[++$i] = $seg;
			unset(self::$_RSG[0]);
		}
	}
		
	private static function _check($var){
		//  get the server variable.
		$path = isset($_SERVER[$var])? $_SERVER[$var] : @getenv($var);
		if ($path=='' || $path=='/' || $path=='/'.BASE) return false;
		self::$_STR = $path;
		return true;
	}
	
	/** 
	 *  PARSE REQUEST URI
	 *  Clean unusable uri data from server variable and check if it usable.
	**/
	private static function _parse_request(){
		if (!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI']=='') return '';
		//  remove the leading slash
		$ruri = preg_replace("|/(.*)|","\\1",$_SERVER['REQUEST_URI']);
		if ($ruri=='' || $ruri==BASE) return '';
		$path = PATH;
		if (strpos($ruri,'?')) $path.='?';
		$puri = explode("/",$ruri);
		//  find the common parts between $path and $puri (parsed uri)
		$i = 0;
		foreach(explode("/",$path) as $p) if (isset($puri[$i]) && $p==$puri[$i]) ++$i;
		//  remove unused parts
		$puri = implode("/", array_slice($puri, $i));
		if ($puri==BASE) return '';
		if ($puri!='') $puri = '/'.$puri;
		return $puri;
	}
}

?>