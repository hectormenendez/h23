<?php if(!defined('OK')) die('<h1>403</h1>');

class DB_Cache {

	private static $_data = array();

	public static function _construct(){
		// make sure the DB has already made a connection.
		if (is_object(DB::$DB)!==true) DB::error('DBCINI','LIBTIT',__CLASS__);
		// we have to check that the cache path exist and is writable.
		if (!($path=DB::$DB->cachedir)) return DB::$DB->cache = false;
		// add a trailing slash if needed
		$path = preg_replace("/(.+?)\/*$/", "\\1/", $path);
		if (!is_dir($path) || !is_writable($path)) return DB::$DB->cache = false;
		DB::$DB->cachedir = $path;
	}

	// retrieve a cached query
	public static function read($sql=false){
		if (!($data = self::_data($sql)) || !($cont=File::read($data['path'].$data['file']))) return false;
		return unserialize($cont);
	}

	public static function write($sql=false, $obj=false){
		if (!($data=self::_data($sql)) || !$obj) return false;
		// attempt to create the subfolder
		File::mkdir($data['path']);
		if (!File::write($data['path'].$data['file'], serialize($obj))) return false;
		return true;
	}

	public static function delete($seg_one='', $seg_two=''){
		// I'm using an space 'cause if i'd send an empty string _data would return false.
		// and since I'm only interested in retrieving the uri segments, I don't care about the filename.
		$data = self::_data(' ');
		if (!$seg_one) $seg_one = $data['sone'];
		if (!$seg_two) $seg_two = $data['stwo'];
		return File::delete(DB::$DB->cachedir.$seg_one.'+'.$seg_two.'/');
	}

	// retrieve variables from uri so we can determine a path for the cache file
	// oh, and also check if the cache is enabled.
	private static function _data($sql=false){
		if (!$sql || DB::$DB->cache==false) return false;
		// if this function has already been called return data from variable.
		if (count(self::$_data)>0) return self::$_data;
		// the requested URI will become the cache sub-folder and
		// a MD5 hash of the SQL will be the filename
		$data['sone'] = !Uri::segment(1)? 'default' : Uri::segment(1);
		$data['stwo'] = !Uri::segment(2)? 'index' : Uri::segment(2);
		$data['path'] = DB::$DB->cachedir.$data['sone'].'+'.$data['stwo'].'/';
		$data['file'] = md5($sql);
		return $data;
	}
}
?>
