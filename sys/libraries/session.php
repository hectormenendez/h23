<?php if(!defined('OK')) die('<h1>403</h1>');

/**
 * Only the FILE method is currently enabled in sessions.
**/

class Session {

	const _DF = 'ymdHi';		// Date format

	private static $_FI = false; // File Object
	private static $_ID = '';	 // id of the session
	private static $_NOW;		 // The time when session started
	private static $_TTU;		 // Session time to update
	private static $_EXP;		 // Session time to expire
	private static $_MTD;		 // Session storing method
	private static $_CYP;		 // Whether session encrypting is enabled or not
	private static $_NAM;		 // The Session cookie id or table name
	private static $_AM = 		 // Available Session storing  methods
		array('STANDARD', 'FILE','DB');

	/**
	 *  We set the session environment variables and
	 *  define a custom session handler
	**/
	public static function _construct(){
		//  get values from config
		//  We convert minutes to seconds in session update and expire.
		if (!self::$_TTU=round((float)Core::config('sess_update')*60)) self::_error('VARINT','sess_update');
		if (!self::$_EXP=round((float)Core::config('sess_expire')*60)) self::_error('VARINT','sess_expire');
		if (!in_array(strtoupper(self::$_MTD=Core::config('sess_method')),self::$_AM))
			self::_error('VAR404','sess_method["'.self::$_MTD.'"]');
		if (!is_bool(self::$_CYP=Core::config('sess_encrypt'))) self::_error('VARFOR','sess_encrypt');
		self::$_NAM = Core::config('sess_name');
		// set the functions for handling sessions
		$s = 'Session';
		session_set_save_handler(
			array($s, 'open'),
			array($s, 'close'),
			array($s, 'read'),
			array($s, 'write'),
			array($s, 'destroy'),
			array($s, 'gc')
		);
		// register this function at the end of execution so the sessions write everytime
		register_shutdown_function('session_write_close');
	}

	public static function start(){
		// Gets current time, next update, and expiration.
		// Note: i'm  making the last two digits to zero. for rounding purposes.
		self::$_NOW = BMK;
		self::$_TTU = (int)substr((string)self::$_NOW+self::$_TTU,0,-2).'00';
		self::$_EXP = (int)substr((string)self::$_NOW+self::$_EXP,0,-2).'00';
		// set the session id with the IP, User-agent and the fingerkey
		// TODO: Add username for login based frameworks
		session_id(self::$_ID = md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'].Core::config('sess_finger')));
		// set the XML dom document, or create it if non existent.
		if (file_exists(SESS.'session')) XML::load(SESS.'session'); else XML::set(SESS.'session');
		// if we can't find the ID set, create it in the index..
		if (!$element = XML::get_id(self::$_ID))
			XML::append(array('tagname'=>'session','id'=>self::$_ID,'exp'=>self::$_EXP));
		// remove all expired sessions
		self::gc(0);
		// start session
		session_start();
	}

	// opens the file or the db connection
	public static function open($path, $name){
		// open and lock the file
		self::$_FI = @fopen(SESS.self::$_ID,'a+');
		flock(self::$_FI, LOCK_EX);
		return true;
	}

	// reads the file or queries the db
	public static function read($id){
		// make sure the file has something to read.
		if (!self::$_FI || filesize(SESS.self::$_ID)==0) return '';
		// set the pointer to the beginning of the file.
		fseek(self::$_FI, SEEK_SET);
		// read the data
		return fread(self::$_FI, filesize(SESS.self::$_ID));
	}

	// writes changes to files or updates/inserts in the db
	public static function write($id, $data){
		if (!self::$_FI) return false;
		// file exist, lock the file.
		flock(self::$_FI, LOCK_EX);
		// if file has already information replace it.
		if (filesize(SESS.self::$_ID>0)) fseek(self::$_FI, SEEK_SET);
		// write the data
		return fwrite(self::$_FI, $data);
	}

	// closes the file or the db connection
	public static function close(){
		// unlock and close the file
		flock(self::$_FI, LOCK_UN);
		fclose(self::$_FI);
		self::$_FI = null;
		return true;
	}

	// called when session_destroy()
	// eliminates everything from current session.
	public static function destroy($id){
		return @unlink(SESS.self::$_ID);
	}

	// looks for expired sessions and deletes them
	public static function gc($maxlifetime){
		$changed = false;
		// check if the last update value exists and if not,
		// set it to NOW, so the update process runs this time.
		if (($nextupdate = XML::root()->getAttribute('next')) == ""){
			XML::root()->setAttribute('next',$nextupdate = self::$_NOW);
			$changed = true;
		}
		// proceed with the update only if its really time for it.
		if (self::$_NOW >= (float)$nextupdate){
			$sess = XML::query('//session');
			foreach ($sess as $s){
				// if the id has expired delete it.
				if ($s->getAttribute('exp') <= self::$_NOW){
					$s->parentNode->removeChild($s);
					@unlink(SESS.$s->getAttribute('id'));
					$changed = true;
				}
			}
		}
		if ($changed) XML::save();
	}

	private static function _error($code,$var){
		Core::error($code,'LIBTIT',array(__CLASS__,$var));
	}

}
?>
