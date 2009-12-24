<?php if(!defined('OK')) die('<h1>403</h1>');

class DB {

	public static $AUTO = true; 		//  Auto initialize the db?
	public static $INFO = array();	//  Database Connections settings
	public static $PORT = 3306;		//  Default connection port.
	public static $DBUG = false; 	//  Wheter to show database related errors
	public static $DB;

	private static $_DBS;				// Databases set in the configuration file
	private static $_CHS;				// The supported charsets Array
	private static $_DRV = array('mysql','sqlite');	 // Available Drivers (first one will be used as default)

	// The charsets that can be autodetected

	public static final function _construct(){
		self::$DBUG = Core::config('debug');
		// Load the charsets file so we can detect them.
		if (!file_exists(CORE.'charset'.EXT)) self::error('404MSG','LIBTIT',array(__CLASS__,'charset'));
		include CORE.'charset'.EXT;
		self::$_CHS = $_CHS; unset($_CHS);
		// load the default database configuration and
		// check for required values.
		self::$_DBS = Core::config('databases');
		if (!is_array(self::$_DBS)) self::error('ARRTYP','LIBTIT', array(__CLASS__,'databases'));
		if (!count(self::$_DBS)) self::error('ARRNUM','LIBTIT', array(__CLASS__,'databases',1));
	}

	/**
	 *
	**/
	public static function load($paramsorname=false, $return=false){
		// first we have to determine wheter the user is providing a
		// database name, an array of parameters, or a
		// DataSourceName ' dbdriver://username:password@hostname/database'

		if ($paramsorname!==false){
			// parse a datasource name if any
			if (is_string($paramsorname) && strpos($paramsorname,'://')!==false){
				if(($dns = @parse_url($paramsorname))===false) self::error('DBPARM','LIBTIT',__METHOD__);
				// parse params from url
				$paramsorname = array(
					'driver'	=> $dns['scheme'],
					'hostname'	=> (isset($dns['host'])) ? rawurldecode($dns['host']) : null,
					'username'	=> (isset($dns['user'])) ? rawurldecode($dns['user']) : null,
					'password'	=> (isset($dns['pass'])) ? rawurldecode($dns['pass']) : null,
					'name'	=> (isset($dns['path'])) ? rawurldecode(substr($dns['path'], 1)) : null
				);
				// additional config items set?
				if (isset($dns['query'])){
					parse_str($dns['query'], $xtra);
					foreach ($xtra as $key=>$val){
						// convert boolean values (if any)
						if(strtoupper($val)=='TRUE') $val = true;
						elseif(strtoupper($val)=='FALSE') $val = false;
						// assign the parameters
						$paramsorname[$key] = $val;
					}
				}
			// if the user sends a normal string means that we're searching for a database array
			// defined on the configuration, if we can't find it, we send an error.
			} elseif (is_string($paramsorname) && array_key_exists($paramsorname,self::$_DBS) === false)
				self::error('DBEXST','LIBTIT', array(__METHOD__,$paramsorname));
			// at this point we have a valud string pointing to a existent database key, assign it.
			if(is_array($paramsorname)===false) $paramsorname = self::$_DBS[$paramsorname];
		} else {
			// if no parameters provided use the first key defined on the configuration file.
			$paramsorname = Arrays::first(self::$_DBS);
		}
		// Check the database configuration and set default values
		self::$INFO = $paramsorname;
		self::_check();
		// check if the selected driver is available
		if (!in_array(self::$INFO['driver'], self::$_DRV)) self::error('DBDRVR','LIBTIT',array(__CLASS__,self::$INFO['driver']));
		// gentlemen, start ur engines!
		$driver = 'DB_'.self::$INFO['driver'].'_driver';
		return self::$DB = new $driver;
	}

	public static function error($msg,$tit,$xtra=false){
		// if debug is enabled show the error details
		if (self::$DBUG===true) Core::error($msg,$tit,$xtra);
		// or just send a plain error.
		Core::error();
	}

	/**
	 * checks a database configuration array, and set its defaults.
	**/
	private static function _check(){

		// if not database name specified use array's name.
		if (!isset(self::$INFO['name']) || !self::$INFO['name']) self::$INFO['name'] = Arrays::key_first(self::$_DBS);
		// if not hostname specified use localhost
		if (!isset(self::$INFO['hostname']) || !self::$INFO['hostname'])  self::$INFO['hostname'] = 'localhost';
		// if not username or password is specified, send error.
		if (!isset(self::$INFO['username']) || !self::$INFO['username']) self::error('VARREQ','LIBTIT',array(__CLASS__,'username'));
		if (!isset(self::$INFO['password']) || !self::$INFO['password']) self::error('VARREQ','LIBTIT',array(__CLASS__,'password'));
		// self explanatory
		if (!isset(self::$INFO['port']) || !self::$INFO['port']) self::$INFO['port'] = self::$PORT;
		if (!isset(self::$INFO['prefix'])) self::$INFO['prefix'] = '';
		if (!isset(self::$INFO['driver']) || !self::$INFO['driver']) self::$INFO['driver'] = Arrays::first(self::$_DRV);
		if (!isset(self::$INFO['pconnect']) || !is_bool(self::$INFO['pconnect'])) self::$INFO['pconnect'] = true; // persistent connection
		if (!isset(self::$INFO['debug']) || !is_bool(self::$INFO['debug'])) self::$INFO['debug'] = true;
		if (!isset(self::$INFO['cache']) || !is_bool(self::$INFO['cache'])) self::$INFO['cache'] = false;
		if (!isset(self::$INFO['cachedir']) || !self::$INFO['cachedir']) self::$INFO['cachedir'] = CACH.'DB/';
		if (!file_exists(self::$INFO['cachedir'])) mkdir($INFO['cachedir'],0777);
		// send an error if the directory isn't writable
		if (!is_writable(self::$INFO['cachedir'])===false) self::error('403DIR','LIBTIT',array(__CLASS__,'cachedir'));
		// detect charset and collation if necessary
		$chset = Core::config('charset');
		if (!isset(self::$INFO['charset']) || !self::$INFO['charset'])
			self::$INFO['charset'] = array_key_exists($chset, self::$_CHS)? self::$_CHS[$chset][0] : '';
		if (!isset(self::$INFO['collat']) || !self::$INFO['collat'])
			self::$INFO['collat']  = array_key_exists($chset, self::$_CHS)? self::$_CHS[$chset][1] : '';
	}

}
?>
