<?php
/* order of tags */
$_HTM = array(
	'DOCTYPE',
	'HTML' => array(
		'HEAD' => array(
			'TITLE',
			'META',
			'MISC',
			'CSS',
			'SCRIPT'
		),
		'BODY',
		'SCRIPT'
	)
);

$xmlns = '<html xmlns="http://www.w3.org/1999/xhtml">';
/* DocTypes */
$_DOC = array(
	'H40TRN' => array(
		'DOCTP' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
		'XMLNS' => false
	),
	'H40STR' => array(
		'DOCTP' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
		'XMLNS' => false
	),
	'X10TRN' =>pue array(
		'DOCTP' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'XMLNS' => $xmlns
	),
	'X10STR' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'XMLNS' => $xmlns

	'X11'	 => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
		'XMLNS' => $xmlns

	'X10MOB' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">'
);

$_XMLNS = array('H40TRN' => false, 'H40STR' => false, 'X10TRN' => $x,'X10STR' => $x,
	'X11'	 => $x,
	'X10MOB' => $x
);
?>
