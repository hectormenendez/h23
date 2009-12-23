<?php if(!defined('OK')) die('<h1>403</h1>');

$_CFG = array(

	// SHOW DEBUG INFO
	// If you are developing and you encounter an error
	// do you want the framework to show the backtrace along the message error.
	// if you set this to false, the backtrace will be saved inside the temp folder.
	// ** It is very important that you set this to false if you are in production
	//    not doing so will make your application insecure.
	'debug' => true,

	//  DEFAULT CONTROLLER
	'controller' => 'main',

	//  AVAILABLE LANGUAGES
	//  lang-code => language
	//  Note: The first language will be used as default
	'language' => array(
		'es' => 'Español',
		'en' => 'English'
	),

	//  DEFAULT CHARSET
	//  This determines which character set is used by default in various methods
	//  that require a character set to be provided.
	//  ** it is important to provide a valid charset name. otherwise errors will be generated
	// 	** the file /core/charset contains the currently supported charsets.
	//  ** If you know what you are doing, and the charset you need is not supported,
	//	   you can to add it to the file.
	'charset' => 'utf-8',

	//  URL SUFFIX
	//  This option allows you to add a suffix to all URL generated
	'url_suffix' => '.html',

	//  URI PROTOCOL
	//  This item determines which server global should be used to retrieve the
	//  URI string.  The default setting of "AUTO" works for most servers.
	//  The available protocols are : PATH_INFO, QUERY_STRING, REQUEST_URI, ORIG_PATH_INFO
	'uri_protocol' => 'AUTO',

	//  ALLOWED URI CHARACTERS
	//  This lets you specify which characters are permitted within your URLs.
	'uri_chars' => 'a-z 0-9~%.:_-',

	//  ROUTING WILDCARDS
	//  These are just alias for regex used in the routes file.
	//  Leave the array empty if you don't plan tu use wildcards.
	'routing_wildcards' => array(
		':any' => '.+',
		':num' => '[0-9]+'
	),

	//  XML DOCUMENTS VERSION
	//  Specifies which version to use for reading / writing xml files.
	//  this can be overriden with Xml::version(string)
	//  XML PRESERVE WHITE SPACES
	//  wheter to take in consideration spaces in the xml document
	//  this can be overriden with Xml::whitespaces(bool)
	//  XML FORMAT OUTPUT
	//  if you want to generate formatted xml files, set this to true.
	//  note: this is expensive if you plan to handle big xml files.
	//  this can be overriden with Xml::formatoutput(bool)
	'xml_version' 		=> '1.0',
	'xml_white_spaces' 	=> false,
	'xml_format_output' => true,

	// DATABASES
	// these are the settings related to database handling.
	// *  At least one database MUST be configured here. the name is irrelevant.
	// *  if you want to handle more than one database, just add an array after the default one.
	// *  if you don't specify a configuration in this new array, the one defined in the default
	//    array will be used instead.
	// *  the first array defined will always be the default one.
	// *  if you omit to declare a variable a default will be set (even in the default array)
	//    make sure you read the variables explanation. Only username and password are really required here.
	// EXPLANATION OF VARIABLES
	// name		=	The name of the database you want to connect to.
	//				if not provided the array key name will be used.
	// hostname	=	The hostname of your database server (default: localhost).
	// username	=	The username used to connect to your database. (required).
	// password	=	The password used to connect to your database. (required).
	// prefix	=	You can add an optional prefix, which will be added to the table name.
	// driver	=	The database type (default:mysql).
	//				** there's only support for mysql right now.
	// port 	= 	(int) The port used to connect to the database (default is 3306)
	// pconnect	=	(bool) Wheter to use a persistent connection (default:true).
	// debug	= 	(bool) Wheter database errors should be displayed (default:true).
	// cache	=	(bool) Enables / Disables query caching.
	// cachedir	=	The path (must have write permission) where cache files should be stored (default:system cache/DB).
	// charset	=	The charset used in communicating with the database (default:system charset).
	// 				** it will attempt to detect the correct charset based in the /core/charset file.
	// collat	= 	The character collation used for communicating with the database.
	// 				** it will attempt to detect the correct collation based in the /core/charset file.
	'databases'	=> array(
		'framework'	=> array(
			'username'	=> 'root',
			'password'	=> 'Anna23voy',
			'debug'		=> true,
			'cache'		=> true
		)
	),

	// DATABASE FIELD TEMPLATES
	// If you plan to create tables on the fly, these templates will be your best friends,
	// since they will make very easy for you to add fields without the haste of declaring
	// the array everytime, you just provide the id, et voila!
	// for more info on the valid keys for these template, check out the DB_Driver class.
	//
	'db_field_templates' => array(
		'id' => array('NAME'=>'id', 'TYPE' => 'INT', 'LENGTH' => 9, 'AUTO_INCREMENT' => true, 'PRIMARY_KEY' => true)
	),

	//  TIMEZONE
	//  Set the correct timezone.
	//  For a complete list of supported timezones visit:
	//  http://www.php.net/manual/en/timezones.php
	'time_zone' => 'America/Cancun',

	//  SESSION VARIABLES
	//  Variables for handling sessions
	//  sess_method  = Session storing method can be STANDARD, FILE or DB.
	//  sess_name    = The name of the Cookie or Database Table name.
	//  sess_expire  = The number of (float)MINUTES you want the session to last.
	//  sess_update  = How many (float)MINUTES to wait before refreshing Session Information.
	//  sess_finger	 = Security keyword for the session (you MUST change this to whatever you want)
	//  sess_encrypt = Wheter you like or not to encrypt session info.

	'sess_method'  => 'FILE',
	'sess_name'    => '',
	'sess_expire'  => 120, // 60*24*365 = 1 year.
	'sess_update'  => 5,
	'sess_finger'  => 'Aa3dss',
	'sess_encrypt' => false,
	'sess_cookies' => false,

	//  GLOBAL XSS FILTERING (could slow down display of pages)
	//  Determines whether the XSS filter is always active when
	//  GET, POST or COOKIE data is encountered
	'xss_filtering' => false,

	//  OUTPUT COMPRESSION
	//  Enables Gzip output compression for faster page loads.  When enabled,
	//  the output class will test whether your server supports Gzip.
	'output_gzip' => false,

	//  REWRITE PHP SHORT TAGS
	//  If your PHP installation does not have short tag support enabled
	//  the framework can rewrite the tags on-the-fly, enabling you to
	//  utilize that syntax in your view files.
	'force_short_tags' => false,

	//  JAVASCRIPT PACKER
	//  Dean Edwards algorithm for compress and / or obfuscate javascript code.
	//  js_packer_encoding	= 	 level of encoding, int or string :
	//  					  	 0,10,62,95 or 'None', 'Numeric', 'Normal', 'High ASCII'.
	// 						  	 default:62
	//  js_packer_fastdecode = 	 include the fast decoder in the packed result, boolean.
	//						     default: true
	//  js_packer_specialchars = if you are flagged your private and local variables
	//  						 in the script, boolean.
	//							 default: false.
	'js_packer_encoding' 	 => 62,
	'js_packer_fastdecode' 	 => true,
	'js_packer_specialchars' => false,
	'js_packer_ignorecase'	 => false,
	'js_packer_escapechar'	 => '\\'

);
?>
