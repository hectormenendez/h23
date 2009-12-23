<?php if(!defined('OK')) die('<h1>403</h1>');

class Core {

	private static $_LIB;		//  Loaded Libraries
	private static $_CFG;		//  main config array
	private static $_CNT;		//  system content array
	private static $_LNG;		//  The current Language
	private static $_GLB;		//  The Globals array
	private static $_ELV;		//  Error Levels
	private static $_CCC; 		//  Current controller object
	private static $_ERS;		//  Wheter an Error has been sent to the output;
	/**
	 *  LIBRARIES AUTOLOADER
	 *  Whenever a class is called this method triggers
	 *  and includes the corresponding library.
	**/
	public static function libraries($class = false){
		//  Mark core as a loaded library
		if (!self::$_LIB) self::$_LIB = array('core' => true);
		//  if no argument is specified return
		//  the array of loaded libs.
		if (!$class) return self::$_LIB;
		//  force lowercase. (we preserve the original case in the $c var)
		$class = strtolower($c=$class);
		//  I don't know if this is really necessary
		//  but I don't want any library loaded more
		//  than once, and i don't want to use require_once.
		if (isset(self::$_LIB[$c])) return;
		$file = $class;
		//  check if file exists and load it.
		// if the name contains an underscore it means the lib or helper may be
		// under a directory, try loading it that way.
		if(($ps=strpos($class,'_'))!==false) $file = substr($class,0,$ps)._SH.substr($class,$ps+1);
		if (!file_exists(LIBS.$file.EXT)) {
			// if the library isn't on the libs folder, this may be a helper! try loading it.
			if (!file_exists(HELP.$file.EXT)) self::error('LIB404','LIBTIT',array('',$c));
			else require HELP.$file.EXT;
		} else require LIBS.$file.EXT;
		//  Add the library to the loaded libs array.
		self::$_LIB[$c] = true;
		//  If the library has a constructor, call it.
		//  Note that we are not calling a standard constructor
		//  but a custom method that acts like one, hence the
		//  use of only one dash.
		if (method_exists($class,'_construct')) call_user_func(array($class,'_construct'));
	}

	/**
	 *  INSTANTIATE THE CURRENT CONTROLLER
	 *  keep the current controller object in a variable
	 *  so it can be accessed by the libraries and included files.
	**/
	public static function controller($name=false){
		if ($name) return self::$_CCC = new $name;
		return self::$_CCC? self::$_CCC : false;
	}

	/**
	 *  FLUSH PENDING OUTPUT BUFFERING
	 *  If the current output buffer is greater
	 *  than initial buffer, flush everything.
	**/
	public static function obflush(){
		if (ob_get_level() > OBLVL) {
			ob_end_flush();
			return true;
		}
		return false;
	}

	/**
	 *  SYSTEM CONFIG
	 *  Returns the system config array or an item in it.
	**/
	public static function config($item=false){
		return self::_getext('_CFG', 'config', $item, __METHOD__);
	}

	/**
	 *  SYSTEM GLOBALS
	 *  Returns the system globals array or an item in it.
	**/
	public static function globals($item=false){
		return self::_getext('_GLB', 'globals', $item, __METHOD__);
	}

	/**
	 *  SYSTEM CONTENT
	 *  Returns the system content array or an item in it.
	**/
	public static function content($item=false, $replace=false, $lang=false){
		//  Get the information from external file, using default language
		//  if no other language was specified.
		$cnt = self::_getext('_CNT', 'content', $lang?strtolower($lang):self::language(), __METHOD__);
		//  check if the item exists and return it.
		if (!$item) return $cnt;
		if (is_array($item) && count($item)!==2) self::error('ARRNUM','LIBTIT',array(__METHOD__,'item',2));
		if (is_array($item) && isset($cnt[$item[0]][$item[1]]))
			return $replace? self::replace($cnt[$item[0]][$item[1]],$replace) : $cnt[$item[0]][$item[1]];
		if (!isset($cnt[$item])) self::error('VAR404','LIBTIT',array(__METHOD__,'cnt[item]'));
		return $replace? self::replace($cnt[$item], $replace) : $cnt[$item];
	}

	/**
	 *  REPLACING STRINGS
	 *  Search string for %1%, %2%, etc. and replaces them.
	 *  @param  string  The object to search
	 *  @param  mixed	The string or array of strings to replace with.
	**/
	public static function replace($obj=false, $rep=false){
		if (!$obj || !$rep) self::error('CREREP','LIBTIT',__METHOD__);
		if (is_array($rep)){
			$i = 1;
			foreach($rep as $r){
				$obj = str_replace('%'.$i.'%', $r, $obj);
				++$i;
			}
			return $obj;
		}
		return str_replace('%1%', $rep, $obj);
	}

	/**
	 *  CURRENT SYSTEM LANGUAGE
	 *  This function retrieves the current language or sets a new one.
	 *  Notes:
	 *    - If you don't specify a new language the function
	 *      will return the current language CODE.
	 *    - If you specify a valid language the function will
	 *      return the language NAME for that code.
	**/
	final public static function language($new=false){
		$lang = self::config('language');
		//  If no new language is specified retrieve current or default
		if (!$new){
			//  If there's no current language, retrieve default.
			if (!self::$_LNG) return self::$_LNG = key($lang);
			//  If there's a language defined return its value.
			return self::$_LNG;
		}
		//  check if new language is defined in the config file.
		if (!array_key_exists($new, $lang)) self::error('CRELAN','LIBTIT',array(__METHOD__, $new));
		self::$_LNG = $new;
		return $lang[$new];
	}

	/** TODO: THIS NEEDS TO BE REVIEWED... it was coded in a hurry **/
	public static function debug($backtrace=false, $return=false){
		// if no argument is sent, get the backtrace now.
		if (!$backtrace) $backtrace = debug_backtrace();
		// return false if backtrace isn't array. we don't send an error, cause it'd generate recurssion.
		if (!is_array($backtrace)) return false;
		// we start the file with the current time and date only if we aren't echoing
		$info = $return? '' : '----------------------- '.date("Y/m/d H:i:s")."\n\n";
		// we start the loop through the methods/functions called
		for ($i=count($backtrace)-1; $i>=1; --$i){
			// if this is a require function request skip to next element
			if (isset($backtrace[$i]['function']) && in_array($backtrace[$i]['function'],array('require','include','require_once','include_once')))
				continue;
			// obtain the path of the current function and remove the system path and extension for readibility
			$fl = isset($backtrace[$i]['file'])? str_replace(ROOT,'',$backtrace[$i]['file']) : '';
			$c = (count($e=explode(_SH,$fl))>=2)? 2:1;
			$info.= $e[count($e)-$c].($fl?_SH:'').str_ireplace(EXT,'',end($e));
			$info.= isset($backtrace[$i]['line'])? ':'.$backtrace[$i]['line'].' - ' : '';
			//if ($return) $info.='<b>';
			$info.= isset($backtrace[$i]['class'])? $backtrace[$i]['class'] : '';
			$info.= isset($backtrace[$i]['type'])? $backtrace[$i]['type'] : '';
			$info.= isset($backtrace[$i]['function'])? $backtrace[$i]['function'] : '';
			//if ($return) $info.= '</b>';
			// if the function or method has arguments procede to show them
			if (isset($backtrace[$i]['args']) && count($backtrace[$i]['args'])>=1){
				$info.= '('.self::_debug_args($backtrace[$i]['args']).')';
			} else $info.='()';
			// set a new line unless we're echoing and this is the last line
			$info.=($return && $i==1)? '' : "\n";
		}
		// return the formatted info.
		if ($return) return $info;
		// Save the log file
		// We're counting that TMP is writable since we checked already on index.php
		// if the file hasn't been modified in the last 24 hours, delete it.
		$fc = '';
		if (file_exists($fn = TMP.'backtrace.log')){
			if (filectime($fn)<=(time()-(24*60*60))) unlink($fn);
			// get contents of the file so we can append at the beginning
			else $fc = file_get_contents($fn);
		}
		// open the file and start writting at the top
		$log = fopen($fn,'w');
		fwrite($log,$info."\n".$fc);
		fclose($log);
		return '';
	}

	private static function _debug_args($args,$r='',$recur=false){
		if (!is_array($args)) return $args;
		$i=0; $c = count($args);
		if ($recur) $r.='array(';
		foreach($args as $a){
			if (is_array($a)) $r = self::_debug_args($a,$r,true);
			else {
				    if (is_null($a)) 	$r.= 'null';
				elseif (is_bool($a)) 	$r.= $a?'true':'false';
				elseif (is_object($a)) 	$r.= get_class($a);
				elseif (is_string($a))	$r.= "'$a'";
				elseif (is_numeric($a))	$r.= $a;
				elseif (is_resource($a))$r.= get_resource_type($a);
				else $r.= '['.gettype($a).']';
			}
			if (++$i<$c) $r.=',';
		}
		if ($recur) $r.=')';
		return $r;
	}

	/**
	 *  CUSTOM ERROR HANDLER
	**/
	public static function error($message='DEFMSG', $title='DEFTIT', $var=false, $dg=false, $template='error'){
		if (!file_exists(TMPL.$template.EXT))
			die('<h3>'.__METHOD__.'</h3> The <b>'.$template.'</b> template does not exist.');
		// obtain the the line where this method was called.
		$dg = !$dg? debug_backtrace() : $dg;
		$ln = isset($dg[0]['line'])? $dg[0]['line'] : '';
		$fl = isset($dg[0]['file'])? $dg[0]['file'] : '';
		// obtain the file name and remove the default extension.
		if ($fl) $fl = str_ireplace(EXT,'',substr($fl,strrpos($fl,_SH)+1));
		$line = "$fl:$ln";
		// if debug is enabled send the backtrace info to the template
		// otherwise save the backtrace to a file.
		$debug = '<pre>'.self::debug($dg, (self::config('debug')===true?true:false)).'</pre>';
		//  Check if the user is asking for a content error code.
		$err = self::content('error');
		if (isset($err[$message])) $message = $var? self::replace($err[$message],$var) : $err[$message];
		if (isset($err[$title])) $title = $var? self::replace($err[$title],$var) : $err[$title];
		if ($var){
			// check the message or title for magic constants (__CLASS__ , __METHOD__)
			$message = self::_replacemc($message,$dg);
			$title = self::_replacemc($title,$dg);
		}
		// if the error is generated in an external file (AKA included jx, cssx)
		// just print out the error without templates
		if (_EX) die($title."\n\t".strip_tags($message)."\n\t".$line."\n".strip_tags($debug));
		else {
			//  Output the error template
			self::obflush();
			ob_start();
				// if there's a stylesheet available for the error templates, use it.
				// but only if there isn't already an error stylesheet. since it would be unnecessary.
				if (file_exists(TMPL.'error.css') && !self::$_ERS){
					echo "<style>\n";
					include TMPL.'error.css';
					echo "\n</style>\n";
					self::$_ERS=true;
				}
				require TMPL.$template.EXT;
				$buf = ob_get_contents();
			ob_end_clean();
			die($buf);
		}
	}

	/**
	 *  METHOD AND CLASS REPLACER
	**/
	private static function _replacemc($var, $dg){
		// if the user adds an integer in the constant eg. __METHOD2__
		// he/she's telling the framework to look back in the debug_backtrace array
		// so we have to search for that trace in the array, if we don't find anything
		// use the first trace. ofcourse this only will run if there's more than one trace.
		// ** I added inline tags so i can style the output with css, later on.
		if (preg_match("/__(METHOD|CLASS)-*(\\d*)__/", $var, $r))	{
			$i = ((int)$r[2])+1;
			$class = isset($dg[$i]['class'])? $dg[$i]['class'] : '';
			if (strtoupper($r[1])=='METHOD'){
				$n = '<strong>'.$class.'</strong>'.
					 (isset($dg[$i]['type'])? '<small>'.$dg[$i]['type'].'</small>' : '').
					 (isset($dg[$i]['function'])? '<b>'.$dg[$i]['function'].'</b>' : '');
			} else $n = '<span><strong>'.$class.'</strong>';

			$var = str_replace($r[0],$n,$var);
		}
		return $var;
	}
	/**
	 *  404 ERROR Alias
	**/
	public static function error404($file=' '){
		self::error('404MSG','404TIT',array('',$file), false, 'error_404');
	}

	/**
	 *  PHP ERROR HANDLER
	 *  handles native errors and uses a template to show them.
	 *  TODO: Merge self:error and self::errorphp into one private method
	**/
	public static function errorphp($severity, $message, $path, $line) {
		//  Define Error levels
		if (!self::$_ELV) self::$_ELV = array(
			E_ERROR				=>	'Error',
			E_WARNING			=>	'Warning',
			E_PARSE				=>	'Parsing Error',
			E_NOTICE			=>	'Notice',
			E_CORE_ERROR		=>	'Core Error',
			E_CORE_WARNING		=>	'Core Warning',
			E_COMPILE_ERROR		=>	'Compile Error',
			E_COMPILE_WARNING	=>	'Compile Warning',
			E_USER_ERROR		=>	'User Error',
			E_USER_WARNING		=>	'User Warning',
			E_USER_NOTICE		=>	'User Notice',
			E_STRICT			=>	'Runtime Notice'
		);
		if (!file_exists(TMPL.'error_php'.EXT)) self::error();
		//  Set the severity level from static Error level array if exists.
		$severity = !isset(self::$_ELV[$severity])? $severity : self::$_ELV[$severity];
		//  We hide the full server path for added security
		if (strpos($path,_SH)!==false) {
			$x    = explode(_SH, $path);
			$path = $x[count($x)-2]._SH.end($x);
		}
		// if the error is generated in an external file (AKA included jx, cssx)
		// just print out the error without templates
		if (_EX) echo $severity,"\n\t",strip_tags($message),"\n\t",$path,'(',$line,")\n";
		else {
			//  Flush output buffers (if any) before start buffering this one.
			self::obflush();
			//  Template buffering
			ob_start();
				// if there's a stylesheet available for the error templates, use it.
				// but only if there isn't already an error stylesheet. since it would be unnecessary.
				if (file_exists(TMPL.'error.css') && !self::$_ERS){
					echo "<style>\n";
					include TMPL.'error.css';
					echo "\n</style>\n";
					self::$_ERS=true;
				}
				require TMPL.'error_php'.EXT;
				$buf = ob_get_contents();
			ob_end_clean();
			echo $buf;
		}
	}

	private static function _getext($var, $path, $item, $method){
		//  if file hasn't been loaded yet
		if (!is_array(self::$$var)){
			if (!file_exists(CORE.$path.EXT)) self::error('404MSG','LIBTIT', array($method, $path));
			require_once CORE.$path.EXT;
			if (!isset($$var) || !is_array($$var)) self::error('VARFOR','LIBTIT',array($method, $var));
			self::$$var = &$$var;
			unset($$var);
		}
		//  if no item is specified return the whole array;
		if  (!$item) return self::$$var;
		//  Check that item exists in array
		$arr = &self::$$var;
		if  (!isset($arr["$item"])) self::error('VAR404','LIBTIT',array($method, $var.'['.$item.']'));
		return $arr[$item];
	}

	/**
	 * RETRIEVE THE NAME OF GIVEN VARIABLE
	 * usage: Core::varname($varname, get_defined_vars())
	 * if no scope is given the function will search in the globals array only
	**/
	public static function varname(&$var, $scope=false){
	    $old = $var;
		if (($key=array_search($var=VRND,!$scope?$GLOBALS:$scope))&&(($var=$old)||true)) return $key;
	}

	/**
	 *  ECHOES OR RETRIEVES THE CURRENTLY ELAPSED EXECUTION TIME
	**/
	public static function benchmark($echo=true){
		$bm = round(microtime(true) - BMK,4);
		if (!$echo) return $bm;
		echo ' ',$bm,' ',self::content('seconds');;
	}

	/**
	 *  ECHOES OR RETRIEVES THE CURRENT MEMORY USAGE
	**/
	public static function memory($echo=true, $peek=false){
		$fn = !$peek? 'memory_get_usage' : 'memory_get_peek_usage';
		if (!function_exists($fn)) return false;
		$sz = 'KB';
		if (($mm = $fn()/1024) >= 1024){ $mm = $mm/1024; $sz='MB'; }
		$mm = round($mm,2);
		if (!$echo) return $mm;
		echo ' ',$mm,$sz;
	}

	public static function memorypeek($echo=true, $peek=false){
		self::memory($echo, $peek);
	}
}
?>
