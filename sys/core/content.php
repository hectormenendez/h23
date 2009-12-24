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
			'DBCSET' => 'El charset o collation especificado no es soportado.',
			'DBPARM' => 'No se especifico un parámetro correcto en Load.',
			'DBEXST' => '<b>%2%</b> no esta definida como lista de configuración de base de datos definida.',
			'DBDRVR' => '<b>%2%</b> no es un driver de bases de datos permitido.',
			'DBCONN' => 'No se pudo conectar a la base de datos.',
			'DBNAME' => 'No se especifico el nombre de la  base de datos a conectar.',
			'DBSELE' => 'la base de datos <b>%2%</b> no pudo ser seleccionada.',
			'DBCHAR' => 'No se pudo establecer <b>%2%<i>(%3%)</i></b> como juego de caracteres en la base de datos.',
			'DBCINI' => 'El driver the Bases de Datos debe ser inicializado antes de cualquier llamada a esta libreria.',
			'DBRQRY' => 'No se especifico una consulta.',
			'DBIQRY' => '<small><b>Error en query:</b></small><br> %2%',
			'DBTREQ' => 'Una tabla válida es requerida.',
			'DBTNOF' => 'No se puede crear una tabla sin declarar campos.',
			'DBFREQ' => 'Un campo válido es requerido.',
			'DBTBRN' => 'Debe especificar el nombre de una tabla existente y el nuevo nombre que se le asignara.',
			'DBTBNO' => 'La tabla <b>%2%</b> no existe en la base de datos.',
			'DBFDFO' => 'El formato de declaración del campo es incorrecto.',
			'DBFDNO' => 'El campo <b>%2%</b> no existe en la base de datos.',
			'FTPINV' => 'Parametros inválidos para la creación de una conexión.',
			'FTPUSR' => 'Un nombre de usuario es requerido para la creación de una conexión.',
			'FTPCNN' => 'No fue posible conectarse al servidor.',
			'FTPBLP' => 'No fue posible conectarse al servidor, credenciales incorrectas.',
			'FTPNID' => 'No existe una conexión activa.',
			'FTPLGC' => 'Solo el método FTP::login puede iniciar esta clase.',
			'FTPATH' => 'Debe especificar una ruta válida.',
			'FTPNCD' => 'la ruta <b>%2%</b> no existe.',
			'FTPMKD' => 'El directorio <b>%2%</b> no pudo ser creado. verifique la ruta o si tiene los permisos correctos.',
			'FTPCHM' => 'No fue posible cambiar los permisos de <b>%2%</b>.',
			'FTPRMD' => 'No fue posible eliminar el directorio <b>%2%</b>.',
			'FTPRMF' => 'No fue posible eliminar el archivo <b>%2%</b>.',
			'FTPNMV' => 'No fue posible mover usando la ruta especificada.',
			'FTPUPL' => 'No fue posible transferir el archivo <b>%2%</b>.'
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
