<?php if(!defined('OK')) die('<h1>403</h1>');
/*
	todo: handle several domdocuments at the same time
		  * _dom() : check that the document has valid XML before attempting to load, so we can prevent errors.
*/
class CSS {

	final public static function _construct(){
		
	}
	
	/**
	 *  ALPHA IN PNG32 Images on IE6
	**/
	public static function png32($url=false, $method=false){
		if(!$method) $method="scale"; else $method="image";
		return "background:none; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=$method, src='$url');";
	}
	
}

?>