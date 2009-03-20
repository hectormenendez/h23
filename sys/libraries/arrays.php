<?php if(!defined('OK')) die('<h1>403</h1>');

class Arrays {
	
	/*
	final public static _construct(){
		
	}
	*/
	
	/**
	 * Return the first element of an array
	**/
	public static function first($array=false){
		self::_check($array);
		// we don't need to look for the first element because the pointer
		// was reset since we didn't pass the array as reference, so a copy was made.
		return current($array);
	}
	
	/** 
	 *  Return the key of the first element of an array.
	**/
	public static function key_first($array=false){
		self::_check($array);
		// we don't need to look for the first element because the pointer
		// was reset since we didn't pass the array as reference, so a copy was made.
		return key($array);
	}

	/** 
	 *  Returns an exact copy of an array, including its pointer position
	**/
	public static function copy(&$array=false){
		self::_check($array);
		$key = key($array); $copy = $array;
		while (($cKey = key($copy)) !== null){ if ($cKey==$key) break; next($copy); }
		return $copy;
	}

	/** 
	 *  Search for a KEY with specified variable type exists inside given array
	 *	Note: if a ! is specified before the type, the function will look for the opposite
	 *  @param   string 	'int' or 'string' 
	 *  @param   array    the array to search
	 *  @return  bool
	**/
	public static function key_has_type($type=false, $array=false){
		return self::_types(false, $type, $array, false);
	}

	/** 
	 *  return all KEYS inside array with specified variable type
	 *	Note: if a ! is specified before the type, the function will look for the opposite
	 *  @param string 	'int' or 'string'
	 *  @param array    the array to search
	 *  @return array
	**/
	public static function key_get_type($type=false, $array=false){
		return self::_types(false, $type, $array, true);
	}
	
	/** 
	 *  Search for a VALUE with specified variable type exists inside given array
	 *	Note: if a ! is specified before the type, the function will look for the opposite
	 *  @param  string 	'int','string','array', 'bool'
	 *  @param  array    the array to search
	 *  @return bool
	**/
	public static function value_has_type($type=false, $array=false){
		return self::_types(true, $type, $array, false);
	}

	/** 
	 *  return all KEYS inside array with specified variable type
	 *	Note: if a ! is specified before the type, the function will look for the opposite
	 *  @param  string 	'int','string','array', 'bool'
	 *  @param  array    the array to search
	 *  @return array
	**/
	public static function value_get_type($type=false, $array=false){
		return self::_types(true, $type, $array, true);
	}

	/**
	 *  Handles the 'has_type' and 'get_type' functions
	 * 	 $mode-> true = values    ::  false = keys
	 *	 $all -> true = get_type  ::  false = has_type
	**/
	private static function _types ($mode=false, $type=false, $array=false, $all=false){
		self::_check($array,true);
		$true = true;
		// if type has a ! at the beginning it means that this is a reverse call
		// so we will store all value/types that are NOT of the requested type.
		if(strpos($type,"!")!==false) {
			// remove the !
			$type = substr($type,1);
			// enable reverse lookup
			$true = false;
		}
		// define types available for search  according to $mode (true=values : false=keys)
		$tav = $mode? array('int','string','array','bool') : array('int', 'string');
		// if type provided isn't available show error
		if (!in_array($type, $tav)) Core::error('VARTYP','LIBTIT',array('__METHOD-1__','$type',implode(', ',$tav)));
		// use $type as an alias of a variable type checker function.
		$is_type = 'is_'.$type;
		foreach ($array as $key=>$val){
			// store key/value in array according to $mode (true=values : false=keys)
			$x = $mode? $val : $key;
			// if the key/value is a the type we're lookin for: (unless this is a reverse call)
			// if this is a get_type request ($all) store the matching object
			// if this is a has_type request (!$all) return true;
			if ($is_type($x)===$true) if($all) $r[$key]=$val; else return true; 
		}
		// if $r is not set it means that we couldn't find what we were looking for, return false. 
		if (!isset($r)) return false;
		// otherwise, return the keys/values found
		return $r;
	}

	/**
	 *  Check that is a variable provided and that it is an array
	**/
	private static function _check(&$array=false, $_doublebacktrace=false){
		// if this is a call from _types show the method from two traces back
		$err = array('__METHOD'.(!$_doublebacktrace?'-1':'-2').'__','$array');
		if (!$array) Core::error('VARREQ','LIBTIT',$err);
		if (!is_array($array)) Core::error('ARRTYP','LIBTIT', $err);
	}

	
}