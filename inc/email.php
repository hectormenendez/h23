<?php
	if (count($HTTP_POST_VARS)==0) die('sent 404 header here');
	
	while(list($id, $value) = @each($HTTP_POST_VARS)){
		$$id = $value;
	}
	$head1 = "MIME-Version:1.0\r\n"
			."Content-type: text/html; charset=UTF-8\r\n";
	$head2 = $head1;
	
	$head1 = 'From: '.$nam.'<'.$eml.">\r\n"
			.'Reply-to: '.$eml."\r\n";
	$head2 = "From: Gaceta del Caribe <www@gacetadelcaribe.com>\r\n"
			."Reply-to: contacto@gacetadelcaribe.com\r\n";

	$head1 .= 'X-Mailer: PHP/'.phpversion()."\r\n";
	$head2 .= 'X-Mailer: PHP/'.phpversion()."\r\n";
	
	$ctt .= "\r\n\r\n".$nam."\r\n";
	if(isset($emp)) $ctt.=$emp."\r\n";
	if(isset($tel)) $ctt.=$tel."\r\n";
	if(isset($loc)) $ctt.=$loc."\r\n";
	
	$mail1 = mail('contacto@gacetadelcaribe.com','contacto desde el sitio web',$ctt, $head1);
	$mail2 = mail($eml,'Gaceta del Caribe','su correo ha sido procesado correctamente', $head2);
	
	echo ($mail1 && $mail2)? 'true' : 'false';
?>