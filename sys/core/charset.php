<?php if(!defined('OK')) die('<h1>403</h1>');
	
	// supported character sets/encodings

	$_CHS = array(
		
	// 	CHARSET ALIAS		   DB ALIAS   DEFAULT DB COLLATION 
	
		'big5'		  => array( 'big5'  , 'big5_chinese_ci'   ), // Big5 Traditional Chines
		'cp850'		  => array( 'cp850' , 'cp850_general_ci'  ), // DOS West European
		'cp866'		  => array( 'cp866' , 'cp866_general_ci'  ), // DOS Russian
		'cp852'		  => array( 'cp852' , 'cp852_general_ci'  ), // DOS Central European
		'euc-jp'	  => array( 'ujis'  , 'ujis_japanese_ci'  ), // EUC-JP Japanese
		'euc-kr'	  => array( 'euckr' , 'euckr_korean_ci'   ), // EUC-KR Korean
		'gb2312'	  => array( 'gb2312', 'gb2312_chinese_ci' ), // GB2312 Simplified Chinese
		'iso-8859-1'  => array( 'latin1', 'latin1_swedish_ci' ), // cp1252 West European
		'iso-8859-2'  => array( 'latin2', 'latin2_general_ci' ), // 1SO 8859-2 Central European
		'iso-8859-7'  => array( 'greek' , 'greek_general_ci'  ), // ISO 8859-7 Greek
		'iso-8859-8'  => array( 'hebrew', 'hebrew_general_ci' ), // ISO 8859-8 Hebrew
		'iso-8859-9'  => array( 'latin5', 'latin5_turkish_ci' ), // ISO 8859-9 Turkish
		'iso-8859-13' => array( 'latin7', 'latin7_general_ci' ), // ISO 8859-13 Baltic
		'koi8-r'	  => array( 'koi8r' , 'koi8r_general_ci'  ), // KOI8-R Relcom Russian
		'koi8-u'	  => array( 'koi8u' , 'koi8u_general_ci'  ), // KOI8-U Ukranian
		'shift_jis'   => array( 'sjis'  , 'sjis_japanese_ci'  ), // Shift-JIS Japanese
		'tis-620'	  => array( 'tis620', 'tis620_thai_ci'    ), // TIS620 Thai
		'ucs-2'		  => array( 'ucs2'  , 'ucs2_general_ci'   ), // UCS-2 Unicode
		'us-ascii'	  => array( 'ascii' , 'ascii_general_ci'  ), // US ASCII
		'utf-8'		  => array( 'utf8'  , 'utf8_general_ci'   )  // UTF-8 Unicode
	);

?>