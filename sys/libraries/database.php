<?php if(!defined('OK')) die('<h1>403</h1>');


class Database {


	private static $_DBS;	// Databases set in the configuration file
	private static $_DDB;	// Default database
	private static $_CHS;	// The supported charsets Array
	// The charsets that can be autodetected


	public static function _construct(){
		// Load the charsets file so we can detect them.
		$chset = Core::config('charset');
		if (!file_exists(CORE.'charset'.EXT)) Core::error('404MSG','LIBTIT',array(__CLASS__,'charset'));
		include CORE.'charset'.EXT;
		self::$_CHS = $_CHS; unset($_CHS);
		// load the default database configuration and
		// check for required values.
		self::$_DBS = Core::config('databases');
		if (!is_array(self::$_DBS)) Core::error('ARRTYP','LIBTIT', array(__CLASS__,'databases'));
		if (!count(self::$_DBS)) Core::error('ARRNUM','LIBTIT', array(__CLASS__,'databases',1));
		// we get the default database and check for the correct settings
		self::$_DDB = Arrays::first(self::$_DBS);


		echo key(self::$_DBS);
		var_dump(self::$_DDB);
	}


	/**
	 *
	**/
	public static function load($paramsorname=false, $return=false){
		// first we have to determine wheter the user is providing a
		// database name, an array of parameters, or a
		// DataSourceName ' dbdriver://username:password@hostname/database '


	}
	/**
	 * checks a database configuration array, and set its defaults.
	**/
	private static function _checkconfig($dbname, $isdef=false){
		// if not database name specified use array's name.
		if (!isset(self::$_DDB['name']) || !self::$_DDB['name']) self::$_DDB['name'] = Arrays::key_first(self::$_DBS);
		// if not hostname specified use localhost
		if (!isset(self::$_DDB['hostname']) || !self::$_DDB['hostname'])  self::$_DDB['hostname'] = 'localhost';
		// if not username or password is specified, send error.
		if (!isset(self::$_DDB['username']) || !self::$_DDB['username']) Core::error('VARREQ','LIBTIT',array(__CLASS__,'username'));
		if (!isset(self::$_DDB['password']) || !self::$_DDB['password']) Core::error('VARREQ','LIBTIT',array(__CLASS__,'password'));
		// self explanatory
		if (!isset(self::$_DDB['prefix'])) self::$_DDB['prefix'] = '';
		if (!isset(self::$_DDB['driver']) || !self::$_DDB['driver']) self::$_DDB['driver'] = 'mysql';
		if (!isset(self::$_DDB['pconnect']) || !is_bool(self::$_DDB['pconnect'])) self::$_DDB['pconnect'] = true;
		if (!isset(self::$_DDB['debug']) || !is_bool(self::$_DDB['debug'])) self::$_DDB['debug'] = true;
		if (!isset(self::$_DDB['cache']) || !is_bool(self::$_DDB['cache'])) self::$_DDB['cache'] = false;
		if (!isset(self::$_DDB['cachedir']) || !self::$_DDB['cachedir']) self::$_DDB['cachedir'] = CACH;
		if (!is_writable(self::$_DDB['cachedir'])) Core::error('403DIR','LIBTIT',array(__CLASS__,'cachedir'));
		// detect charset and collation if necessary
		if (!isset(self::$_DDB['charset']) || !self::$_DDB['charset'])
			self::$_DDB['charset'] = array_key_exists($chset, self::$_CHS)? self::$_CHS[$chset][0] : '';
		if (!isset(self::$_DDB['collat']) || !self::$_DDB['collat'])
			self::$_DDB['collat']  = array_key_exists($chset, self::$_CHS)? self::$_CHS[$chset][1] : '';


	}


}
?>
