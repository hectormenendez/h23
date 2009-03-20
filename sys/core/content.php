<?php if(!defined('OK')) die('<h1>403</h1>');

	$_CNT['es'] = array(
		'seconds' => 'segundos',

		'error' => array(
				// The first two elements define the default error shown 
				// if the method is called without arguments
			'DEFTIT' => 'Error',
			'DEFMSG' => '',
			'404TIT' => 'Error <b>404</b>',
			'404MSG' => 'El archivo <b>%2%</b> no pudo ser encontrado.',
			'403MSG' => 'El archivo <b>%2%</b> no tiene los permisos correctos.',
			'404DIR' => 'El directorio <b>%2%</b> no pudo ser encontrado.',
			'403DIR' => 'El directorio <b>%2%</b> no tiene los permisos correctos.',
			'LIBTIT' => 'Error en Libreria <span>%1%</span>',
			'LIB404' => 'La libreria <b>%2%</b> no fue encontrada.',
			'CREREP' => '<b>obj</b> y <b>rep</b> son requeridos',
			'CRELAN' => '<b>%2%</b> no es un codigo de idioma definido.',
			'RTRFRM' => 'El formato en la matriz routes es incorrecto.',
			'RTRCTR' => '<b>default_controller</b> no ha sido especificado en la configuracion, imposible continuar.',
			'RTRCTR' => 'El controlador predeterminado no existe',
			'VARREQ' => '<b>%2%</b> es requerido.',
			'VARTYP' => '<b>%2%</b> tiene que ser del tipo <b>%3%</b>.',
			'VAR404' => '<b>%2%</b> no esta definido.',
			'VARFOR' => '<b>%2%</b> no tiene un formato correcto.',
			'VARINT' => '<b>%2%</b> tiene que ser de tipo int().',
			'ARRTYP' => '<b>%2%</b> tiene que ser del tipo array().',
			'ARRNUM' => 'El array <b>%2%</b> debe contener %3% elemento(s)',
			'XMLROT' => 'No existe un elemento <b>root (documentElement)</b>.',
			'XMLPTH' => 'Es necesario especificar la ruta del archivo xml.',
			'XMLWRI' => 'Permisos insuficientes en el archivo <b>%2%</b>',
			'XMLOBJ' => 'No existe un objecto XML para realizar la accion requerida, especifique una ruta o cree un objecto manualmente.',
			'XMLLDD' => 'Un archivo XML tiene que ser cargado para realizar esta accion.',
			'XMLDIR' => '<b>%2%</b> no pudo ser creado. Su directorio no existe o no tiene los permisos necesarios.',
			'DBCSET' => 'El charset o collation especificado no es soportado.'
		)
		
	);

	$_CNT['en'] = array(
		
		'error' => array(
			'404TIT'  => 'Error <b>404</b>',
			'404MSG'  => 'El archivo <b>%1%</b> no pudo ser encontrado.',
			'LIBTIT'  => 'Error en Librería <b>%1%</b>',
			'RTRFRM'  => 'El formato en la matriz es incorrecto.'
		)
		
	);

?>