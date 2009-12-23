<?php if(!defined('OK')) die('<h1>403</h1>');
/*

	NOTE: I STILL CAN'T FIGURE WHAT'S WRONG HERE... DISABLED ENCODING OF KEYWORDS AND SPECIAL CHARS

*/
class JS {

	private static $_PEN;	// Packer encoding
	private static $_PFD;	// Packer Fast Decode
	private static $_PCH;	// Packer Special Characters
	private static $_PWC;	// Packer Word count
	private static $_PBF;	// Packer Buffer
	private static $_PLE;	// Packer Literal Encoding

	const _PEX = 0;									// Parser EXPRESSION
	const _PRP = 1;									// Parser REPLACEMENT
	const _PLN = 2;									// Parser LENGTH
	private static $_PPB;							// Parser Buffer
	private static $_PIC;							// Parser Ignore Case
	private static $_PEC;							// Parser Escape Char
	private static $_PPG = '/\\(/';					// Parser Groups
	private static $_PPS = '/\\$\\d/';				// Parser sub_Replace
	private static $_PPI = '/^\\$\\d+$/';			// Parser Indexed
	private static $_PPE = '/\\\./';				// Parser Escape
	private static $_PPQ = '/\'/';					// Parser Quote
	private static $_PPD = '/\\x01[^\\x01]*\\x01/';	// Parser Deleted

	private static $_PES = array();					// Parser Escaped
	private static $_PPP = array();					// Parser Patterns

	private static $_FT;

	final public static function _construct(){
		self::$_PLE = array('None'=>0, 'Numeric'=>10, 'Normal'=>62, 'High ASCII'=>95);
		self::$_PFD = Core::config('js_packer_fastdecode');
		self::$_PEN = Core::config('js_packer_encoding');
		self::$_PCH = Core::config('js_packer_specialchars');
		self::$_PIC = Core::config('js_packer_ignorecase');
		self::$_PEC = Core::config('js_packer_escapechar');
	}

	public static function pack ($script, $encoding = null, $specialChars = null, $fastDecode = null) {
		// delete this
		self::$_FT = $FT;
		if (array_key_exists($encoding, self::$_PLE))	$encoding = self::$_PLE[$encoding];
		$script = $script."\n";
		// if nothing specified use the config default values
		self::$_PFD = ($fastDecode   !== null)? $fastDecode : self::$_PFD;
		self::$_PEN = ($encoding     !== null)? min((int)$encoding, 95) : self::$_PEN;
		self::$_PCH = ($specialChars !== null)? $specialChars : self::$_PCH;
		// apply parsing routines
		$script = self::compress($script);
		//if (self::$_PEN) $script = self::_pack_encode_keywords($script);
		//if (self::$_PCH) $script = self::_pack_encode_specialchars($script);
		return $script;
	}

	public static function parse ($string) {
		// execute the global replacement
		self::$_PES = array();
		// simulate the _patterns.toSTring of Dean
		$regexp = '/';
		foreach (self::$_PPP as $reg) $regexp .= '('.substr($reg[self::_PEX], 1, -1).')|';
		$regexp = substr($regexp, 0, -1) . '/';
		$regexp .= (self::$_PIC) ? 'i' : '';
		$string = self::_parse_escape($string, self::$_PEC);
		$string = preg_replace_callback($regexp, array(	'self','_parse_replace'), $string);
		$string = self::_parse_unescape($string, self::$_PEC);
		return preg_replace(self::$_PPD, '', $string);
	}


	/*
		TODO: Load the functions from files and not from constants.
	*/
	public static function get($name) {
		$name = 'self::_JS'.$name;
		if (defined($name)===true) return constant($name);
		die('ERROR :'.$name.' not declared (JS::get)');
	}

	public static function compress($script) {
		// protect strings
		self::_parse_add('/\'[^\'\\n\\r]*\'/', '$1');
		self::_parse_add('/"[^"\\n\\r]*"/', '$1');
		// remove comments
		self::_parse_add('/\\/\\/[^\\n\\r]*[\\n\\r]/', ' ');
		self::_parse_add('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', ' ');
		// protect regular expressions
		self::_parse_add('/\\s+(\\/[^\\/\\n\\r\\*][^\\/\\n\\r]*\\/g?i?)/', '$2'); // _PIN
		self::_parse_add('/[^\\w\\x24\\/\'"*)\\?:]\\/[^\\/\\n\\r\\*][^\\/\\n\\r]*\\/g?i?/', '$1');
		// remove: ;;; doSomething();
		if (self::$_PCH) self::_parse_add('/;;;[^\\n\\r]+[\\n\\r]/');
		// remove redundant semi-colons
		self::_parse_add('/\\(;;\\)/', '$1'); // protect for (;;) loops
		self::_parse_add('/;+\\s*([};])/', '$2');
		// apply the above
		$script = self::parse($script);
		// remove white-space
		self::_parse_add('/(\\b|\\x24)\\s+(\\b|\\x24)/', '$2 $3');
		self::_parse_add('/([+\\-])\\s+([+\\-])/', '$2 $3');
		self::_parse_add('/\\s+/', '');
		// done
		return self::parse($script);
	}

	private static function _pack_encode_keywords($script) {
		// escape high-ascii values already in the script (i.e. in strings)
		if (self::$_PEN > 62) $script = self::_pack_escape95($script);
		// for high-ascii, don't encode single character low-ascii
		$regexp = (self::$_PEN > 62) ? '/\\w\\w+/' : '/\\w+/';
		// build the word list
		$keywords = self::_pack_analyze($script, $regexp, self::_pack_get_encoder(self::$_PEN));
		$encoded = $keywords['encoded'];
		// encode
		self::_parse_add($regexp, array('fn' => '_parse_replace_encoded', 'data' => $encoded));
		if (empty($script)) return $script;
		else return self::_pack_bootstrap($script = self::parse($script) , $keywords);
	}

	private static function _pack_encode_specialchars($script) {
		// replace: $name -> n, $$name -> na
		self::_parse_add('/((\\x24+)([a-zA-Z$_]+))(\\d*)/', array('fn' => '_parse_replace_name'));
		// replace: _name -> _0, double-underscore (__name) is _PINd
		$regexp = '/\\b_[A-Za-z\\d]\\w*/';
		// build the word list
		$keywords = self::_pack_analyze($script, $regexp, '_pack_encode_private');
		// quick ref
		$encoded = $keywords['encoded'];

		self::_parse_add($regexp, array('fn' => '_parse_replace_encoded','data' => $encoded)	);
		return self::parse($script);
	}

	private static function _pack_escape95($script){
		return preg_replace_callback('/[\\xa1-\\xff]/',	array('self', '_pack_escape95_callback'), $script);
	}

	private static function _pack_escape95_callback($match){
		return '\x'.((string)dechex(ord($match)));
	}

	private static function _pack_analyze($script, $regexp, $encode) {
		$wall = array(); // all words in the script
		// get all words
		preg_match_all($regexp, $script, $wall);
		// simulate the javascript comportement of global match
		$wall = $wall[0];
		if (empty($wall)) return false;
		$wsort = array(); // list of words sorted by frequency
		$wusrt = array(); // list of words without sorting
		$wprot = array(); // instances of "protected" words
		$wenco = array(); // dictionary of word->encoding
		$dprot = array(); // dictionary of word->"word"
		$value = array(); // dictionary of charCode->encoding (eg. 256->ff)
		self::$_PWC = array(); // word->count
		$i = count($wall); $j = 0; //$word = null;
		// count the occurrences - used for sorting later
		do {
			--$i;
			$word = '$' . $wall[$i];
			if (!isset(self::$_PWC[$word])) {
				self::$_PWC[$word] = 0;
				$wusrt[$j] = $word;
				// make a dictionary of all of the protected words in this script
				//  these are words that might be mistaken for encoding
				$value[$j] = call_user_func(array('self', $encode), $j);
				$dprot['$'.$value[$j]] = $j++;
			}
			// increment the word counter
			self::$_PWC[$word]++;
		} while ($i > 0);
		// prepare to sort the word list, first we must protect
		//  words that are also used as codes. we assign them a code
		//  equivalent to the word itself.
		// e.g. if "do" falls within our encoding range
		//      then we store keywords["do"] = "do";
		// this avoids problems when decoding
		$i = count($wusrt);
		do {
			$word = $wusrt[--$i];
			if ( isset($dprot[$word]) /*!= null*/) {
				$wsort[$dprot[$word]] = substr($word, 1);
				$iprot[$dprot[$word]] = true;
				self::$_PWC[$word] = 0;
			}
		} while ($i);

		// sort the words by frequency
		// Note: the javascript and php version of sort can be different :
		// in php manual, usort :
		// " If two members compare as equal,
		// their order in the sorted array is undefined."
		// so the final packed script is different of the Dean's javascript version
		// but equivalent.
		// the ECMAscript standard does not guarantee this behaviour,
		// and thus not all browsers (e.g. Mozilla versions dating back to at
		// least 2003) respect this.
		usort($wusrt, array('self', '_pack_sortwords'));
		$j = 0;
		// because there are "protected" words in the list
		//  we must add the sorted words around them
		do {
			if (!isset($wsort[$i]))	$wsort[$i] = substr($wusrt[$j++], 1);
			$wenco[$wsort[$i]] = $value[$i];
		} while (++$i < count($wusrt));

		return array('sorted'  => $wsort, 'encoded' => $wenco,	'protected' => $wprot);
	}

	private static function _pack_sortwords($match1, $match2) {
		return self::$_PWC[$match2] - self::$_PWC[$match1];
	}

	private static function _pack_get_encoder($ascii){
		return $ascii > 10 ? $ascii > 36 ? $ascii > 62 ?
			'_pack_encode95' : '_pack_encode62' : '_pack_encode36' : '_pack_encode10';
	}

	// zero encoding
	// characters: 0123456789
	private static function _pack_encode10($charCode) {
		return $charCode;
	}

	// inherent base36 support
	// characters: 0123456789abcdefghijklmnopqrstuvwxyz
	private static function _pack_encode36($charCode) {
		return base_convert($charCode, 10, 36);
	}

	// hitch a ride on base36 and add the upper case alpha characters
	// characters: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
	private static function _pack_encode62($charCode) {
		$res = '';
		if ($charCode >= self::$_PEN) $res = self::_pack_encode62((int)($charCode / self::$_PEN));
		$charCode = $charCode % self::$_PEN;
		if ($charCode > 35) return $res.chr($charCode + 29);
		else return $res.base_convert($charCode, 10, 36);
	}

	// use high-ascii values
	// characters: ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþ
	private static function _pack_encode95($charCode) {
		$res = '';
		if ($charCode >= self::$_PEN) $res = self::_pack_encode95($charCode / self::$_PEN);
		return $res.chr(($charCode % self::$_PEN)+161);
	}

	private static function _pack_encode_private($charCode) {
		return "_" . $charCode;
	}
	// build the boot function used for loading and decoding
	private static function _pack_bootstrap($packed, $keywords) {
		$ENCODE = self::_pack_safe_regex('$encode\\($count\\)');
		// $packed: the packed script
		$packed = "'" . self::_pack_escape($packed) . "'";
		// $ascii: base for encoding
		$ascii = min(count($keywords['sorted']), self::$_PEN);
		if ($ascii == 0) $ascii = 1;
		// $count: number of words contained in the script
		$count = count($keywords['sorted']);
		// $keywords: list of words contained in the script
		foreach ($keywords['protected'] as $i=>$value) 	$keywords['sorted'][$i] = '';
		// convert from a string to an array
		ksort($keywords['sorted']);
		$keywords = "'" . implode('|',$keywords['sorted']) . "'.split('|')";
		$encode = (self::$_PEN > 62)? '_pack_encode95' : self::_pack_get_encoder($ascii);
		$encode = self::get('_encode'.self::$_PEN);
		$encode = preg_replace('/_encoding/','$ascii', $encode);
		$encode = preg_replace('/arguments\\.callee/','$encode', $encode);
		$inline = '\\$count' . ($ascii > 10 ? '.toString(\\$ascii)' : '');
		// $decode: code snippet to speed up decoding
		if (self::$_PFD) {
			// create the decoder
			$decode = self::get('_decode_body');
			if (self::$_PEN > 62) $decode = preg_replace('/\\\\w/', '[\\xa1-\\xff]', $decode);
			// perform the encoding inline for lower ascii values
			elseif ($ascii < 36)
				$decode = preg_replace($ENCODE, $inline, $decode);
			// special case: when $count==0 there are no keywords. I want to keep
			//  the basic shape of the unpacking funcion so i'll frig the code...
			if ($count == 0)
				$decode = preg_replace(self::_pack_safe_regex('($count)\\s*=\\s*1'), '$1=0', $decode, 1);
		}
		// boot function
		$unpack = self::get('_unpack');
		if (self::$_PFD) {
			// insert the decoder
			self::$_PBF = $decode;
			$unpack = preg_replace_callback('/\\{/', array('self', '_pack_put_fdecode'), $unpack, 1);
		}
		$unpack = preg_replace('/"/', "'", $unpack);
		if (self::$_PEN > 62) { // high-ascii
			// get rid of the word-boundaries for regexp matches
			$unpack = preg_replace('/\'\\\\\\\\b\'\s*\\+|\\+\s*\'\\\\\\\\b\'/', '', $unpack);
		}
		if ($ascii > 36 || self::$_PEN > 62 || self::$_PFD) {
			// insert the encode function
			self::$_PBF = $encode;
			$unpack = preg_replace_callback('/\\{/', array('self', '_pack_put_fencode'), $unpack, 1);
		} else {
			// perform the encoding inline
			$unpack = preg_replace($ENCODE, $inline, $unpack);
		}
		// pack the boot function too
		self::pack($unpack, 0, true, false, false);
		// arguments
		$params = array($packed, $ascii, $count, $keywords);
		if (self::$_PFD) {
			$params[] = 0;
			$params[] = '{}';
		}
		$params = implode(',', $params);

		// the whole thing
		return 'eval(' . $unpack . '(' . $params . "))\n";
	}

	private static function _pack_safe_regex($string) {
		return '/'.preg_replace('/\$/', '\\\$', $string).'/';
	}

	private static function _pack_escape($script) {
		return preg_replace('/([\\\\\'])/', '\\\$1', $script);
	}

	private static function _pack_put_fdecode($match) {
		return '{' . self::$_PBF . ';';
	}

	private static function _pack_put_fencode($match) {
		return '{'."\n\t".'$encode=' . self::$_PBF . ';';
	}

	// ------------------------------------------------------------------------------------------------------

	private static function _parse_add($expression, $replacement = '') {
		// count the number of sub-expressions
		//  - add one because each pattern is itself a sub-expression
		$length = 1 + preg_match_all(self::$_PPG, preg_replace(self::$_PPE,'', (string)$expression), $out);
		// treat only strings $replacement
		if (is_string($replacement)) {
			// does the pattern deal with sub-expressions?
			if (preg_match(self::$_PPS, $replacement)) {
				// a simple lookup? (e.g. "$2")
				if (preg_match(self::$_PPI, $replacement)) {
					// store the index (used for fast retrieval of matched strings)
					$replacement = (int)(substr($replacement, 1)) - 1;
				} else { // a complicated lookup (e.g. "Hello $2 $1")
					// build a function to do the lookup
					$quote = preg_match(self::$_PPQ, preg_replace(self::$_PPE,'', $replacement))? '"' : "'";
					$replacement = array(
						'fn' => '_parse_back_refs',
						'data' => array('replacement' => $replacement, 'length' => $length,	'quote' => $quote)
					);
				}
			}
		}
		// pass the modified arguments
		if (!empty($expression)) self::_parse_add_pattern($expression, $replacement, $length);
		else self::_parse_add_pattern('/^$/', $replacement, $length);
	}

	private static function _parse_add_pattern(){
		$arguments = func_get_args();
		self::$_PPP[] = $arguments;
	}

	// this is the global replace function (it's quite complicated)
	private static function _parse_replace($arguments) {
		if (empty($arguments)) return '';
		$i = 1; $j = 0;
		// loop through the patterns
		while (isset(self::$_PPP[$j])) {
			$pattern = self::$_PPP[$j++];
			// do we have a result?
			if (isset($arguments[$i]) && ($arguments[$i] != '')) {
				$replacement = $pattern[self::_PRP];
				if (is_array($replacement) && isset($replacement['fn'])) {
					if (isset($replacement['data'])) self::$_PPB = $replacement['data'];
					return call_user_func(array('self', $replacement['fn']), $arguments, $i);
				}
				elseif (is_int($replacement)) return $arguments[$replacement + $i];
				$delete = (self::$_PEC == '' || strpos($arguments[$i], self::$_PEC) === false)? '' : "\x01".$arguments[$i]."\x01";
				return $delete . $replacement;
			// skip over references to sub-expressions
			} else $i += $pattern[self::_PLN];
		}
	}

	private static function _parse_back_refs($match, $offset) {
		$replacement = self::$_PPB['replacement'];
		$quote = self::$_PPB['quote'];
		$i = self::$_PPB['length'];
		while ($i) { $replacement = str_replace('$'.$i--, $match[$offset + $i], $replacement); }
		return $replacement;
	}

	private static function _parse_replace_name($match, $offset){
		$length = strlen($match[$offset + 2]);
		$start = $length - max($length - strlen($match[$offset + 3]), 0);
		return substr($match[$offset + 1], $start, $length) . $match[$offset + 4];
	}

	private static function _parse_replace_encoded($match, $offset) {
		return isset(self::$_PPB[$match[$offset]])? self::$_PPB[$match[$offset]] : '';
	}

	private static function _parse_escape($string, $escapeChar) {
		if ($escapeChar) {
			self::$_PPB = $escapeChar;
			return preg_replace_callback('/\\'.$escapeChar.'(.)'.'/', array('self','_parse_escape_callback'),$string);
		} else return $string;
	}

	private static function _parse_escape_callback($match){
		self::$_PES[] = $match[1];
		return self::$_PPB;
	}

	// decode escaped characters
	private static function _parse_unescape($string, $escapeChar) {
		if ($escapeChar) {
			$regexp = '/'.'\\'.$escapeChar.'/';
			self::$_PPB = array('escapeChar'=> $escapeChar, 'i' => 0);
			return (preg_replace_callback($regexp, array('self', '_parse_unescape_callback'), $string));
		} else return $string;
	}

	private static function _parse_unescape_callback() {
		if (isset(self::$_PES[self::$_PPB['i']]) && self::$_PES[self::$_PPB['i']] != '') $temp = self::$_PES[self::$_PPB['i']];
		else $temp = '';
		self::$_PPB['i']++;
		return self::$_PPB['escapeChar'] . $temp;
	}


	/**
	 *	JAVASCRIPT FUNCTIONS
	 * ---------------------
	 * Note :  In Dean's version, these functions are converted
	 * 		with 'String(aFunctionName);'.
	 * 		This internal conversion complete the original code, ex :
	 * 		'while (aBool) anAction();' is converted to
	 * 		'while (aBool) { anAction(); }'.
	 * 		The JavaScript functions below are corrected.
	**/

	// unpacking function - this is the boot strap function
	//  data extracted from this packing routine is passed to
	//  this function when decoded in the target
	// NOTE ! : without the ';' final.
	const _JS_unpack ='
function($packed, $ascii, $count, $keywords, $encode, $decode) {
	while ($count--) {if ($keywords[$count]) $packed = $packed.replace(new RegExp(\'\\\\b\' + $encode($count) + \'\\\\b\', \'g\'), $keywords[$count]);}
	return $packed;
}';

	// code-snippet inserted into the unpacker to speed up decoding
	//_decode = function() {
	// does the browser support String.replace where the
	//  replacement value is a function?
	const _JS_decode_body ='
	if (!\'\'.replace(/^/, String)) {
		// decode all the values we need
		while ($count--) {
			$decode[$encode($count)] = $keywords[$count] || $encode($count);
		}
		// global replacement function
		$keywords = [function ($encoded) {return $decode[$encoded]}];
		// generic match
		$encode = function () {return \'\\\\w+\'};
		// reset the loop counter -  we are now doing a global replace
		$count = 1;
	}';

	// zero encoding
	// characters: 0123456789
	const _JS_encode10 =
'function($charCode) {
		return $charCode;
}';//;';

	// inherent base36 support
	// characters: 0123456789abcdefghijklmnopqrstuvwxyz
	const _JS_encode36 =
'function($charCode) {
		return $charCode.toString(36);
}';//;';

	// hitch a ride on base36 and add the upper case alpha characters
	// characters: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
	const _JS_encode62 =
'function($charCode) {
		return ($charCode < _encoding ? \'\' : arguments.callee(parseInt($charCode / _encoding))) +
		(($charCode = $charCode % _encoding) > 35 ? String.fromCharCode($charCode + 29) : $charCode.toString(36));
	}';

	// use high-ascii values
	// characters: ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþ
	const _JS_encode95 =
'function($charCode) {
	return ($charCode < _encoding ? \'\' : arguments.callee($charCode / _encoding)) +
		String.fromCharCode($charCode % _encoding + 161);
}';

}
?>
